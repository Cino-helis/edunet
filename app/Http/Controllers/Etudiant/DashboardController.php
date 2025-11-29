<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $etudiant = auth()->user()->etudiant;
        
        // Inscription active
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2024-2025')
            ->with(['filiere', 'niveau'])
            ->first();
        
        // Statistiques générales
        $stats = $this->getGeneralStats($etudiant);
        
        // Notes récentes (10 dernières)
        $notesRecentes = $etudiant->notes()
            ->with(['matiere', 'enseignant'])
            ->latest('date_saisie')
            ->take(10)
            ->get();
        
        // Statistiques par matière
        $statsParMatiere = $this->getStatsParMatiere($etudiant);
        
        // Progression (notes par mois)
        $progression = $this->getProgression($etudiant);
        
        // Classement (position dans la promo)
        $classement = $this->getClassement($etudiant, $inscriptionActive);
        
        return view('etudiant.dashboard', compact(
            'etudiant',
            'inscriptionActive',
            'stats',
            'notesRecentes',
            'statsParMatiere',
            'progression',
            'classement'
        ));
    }
    
    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats($etudiant)
    {
        $notes = $etudiant->notes;
        $totalNotes = $notes->count();
        
        // Moyenne générale
        $moyenneGenerale = $totalNotes > 0 ? $notes->avg('valeur') : 0;
        
        // Taux de réussite
        $notesReussies = $notes->where('valeur', '>=', 10)->count();
        $tauxReussite = $totalNotes > 0 ? ($notesReussies / $totalNotes) * 100 : 0;
        
        // Nombre de matières suivies
        $nbMatieres = $notes->unique('matiere_id')->count();
        
        // Notes ce mois
        $notesCeMois = $notes->where('date_saisie', '>=', Carbon::now()->startOfMonth())->count();
        
        // Meilleure note
        $meilleureNote = $totalNotes > 0 ? $notes->max('valeur') : 0;
        
        // Pire note
        $pireNote = $totalNotes > 0 ? $notes->min('valeur') : 0;
        
        return [
            'total_notes' => $totalNotes,
            'moyenne_generale' => round($moyenneGenerale, 2),
            'taux_reussite' => round($tauxReussite, 1),
            'nb_matieres' => $nbMatieres,
            'notes_ce_mois' => $notesCeMois,
            'meilleure_note' => round($meilleureNote, 2),
            'pire_note' => round($pireNote, 2),
        ];
    }
    
    /**
     * Obtenir les statistiques par matière
     */
    private function getStatsParMatiere($etudiant)
    {
        return $etudiant->notes()
            ->with('matiere')
            ->get()
            ->groupBy('matiere_id')
            ->map(function ($notesMatiere) {
                $matiere = $notesMatiere->first()->matiere;
                $moyenne = $notesMatiere->avg('valeur');
                $nbNotes = $notesMatiere->count();
                
                return [
                    'matiere' => $matiere,
                    'moyenne' => round($moyenne, 2),
                    'nb_notes' => $nbNotes,
                    'derniere_note' => $notesMatiere->sortByDesc('date_saisie')->first(),
                    'progression' => $this->calculateProgression($notesMatiere),
                ];
            })
            ->sortByDesc('moyenne')
            ->values();
    }
    
    /**
     * Calculer la progression dans une matière
     */
    private function calculateProgression($notes)
    {
        if ($notes->count() < 2) {
            return 0;
        }
        
        $notesOrdered = $notes->sortBy('date_saisie')->values();
        $premiere = $notesOrdered->first()->valeur;
        $derniere = $notesOrdered->last()->valeur;
        
        return round($derniere - $premiere, 2);
    }
    
    /**
     * Obtenir la progression mensuelle
     */
    private function getProgression($etudiant)
    {
        $derniersMois = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $notes = $etudiant->notes()
                ->whereYear('date_saisie', $date->year)
                ->whereMonth('date_saisie', $date->month)
                ->get();
            
            $derniersMois[] = [
                'mois' => $date->locale('fr')->isoFormat('MMM YYYY'),
                'moyenne' => $notes->count() > 0 ? round($notes->avg('valeur'), 2) : null,
                'nb_notes' => $notes->count(),
            ];
        }
        
        return $derniersMois;
    }
    
    /**
     * Obtenir le classement de l'étudiant
     */
    private function getClassement($etudiant, $inscriptionActive)
    {
        if (!$inscriptionActive) {
            return null;
        }
        
        // Récupérer tous les étudiants du même niveau
        $etudiantsNiveau = \App\Models\Etudiant::whereHas('inscriptions', function($query) use ($inscriptionActive) {
            $query->where('niveau_id', $inscriptionActive->niveau_id)
                  ->where('statut', 'en_cours')
                  ->where('annee_academique', '2024-2025');
        })->get();
        
        // Calculer la moyenne de chaque étudiant et trier
        $classement = $etudiantsNiveau->map(function($etu) {
            $moyenne = $etu->notes->avg('valeur') ?? 0;
            return [
                'etudiant' => $etu,
                'moyenne' => round($moyenne, 2),
                'nb_notes' => $etu->notes->count(),
            ];
        })
        ->sortByDesc('moyenne')
        ->values();
        
        // Trouver la position de l'étudiant actuel
        $position = $classement->search(function($item) use ($etudiant) {
            return $item['etudiant']->id === $etudiant->id;
        }) + 1;
        
        return [
            'position' => $position,
            'total' => $classement->count(),
            'top3' => $classement->take(3),
        ];
    }
}