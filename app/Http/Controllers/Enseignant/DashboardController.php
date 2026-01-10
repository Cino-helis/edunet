<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enseignant;
use App\Models\Note;
use App\Models\Matiere;
use App\Models\Etudiant;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer l'enseignant connecté
        $enseignant = auth()->user()->enseignant;
        
        // Statistiques générales
        $stats = $this->getGeneralStats($enseignant);
        
        // Affectations (matières enseignées)
        $affectations = $enseignant->affectations()
            ->with(['matiere', 'niveau.filiere'])
            ->where('annee_academique', '2025-2026')
            ->get();
        
        // Notes récemment saisies
        $notesRecentes = $enseignant->notes()
            ->with(['etudiant', 'matiere'])
            ->latest('date_saisie')
            ->take(10)
            ->get();
        
        // Statistiques par matière
        $statsParMatiere = $this->getStatsParMatiere($enseignant);
        
        return view('enseignant.dashboard', compact(
            'enseignant',
            'stats',
            'affectations',
            'notesRecentes',
            'statsParMatiere'
        ));
    }
    
    /**
     * Obtenir les statistiques générales
     */
    private function getGeneralStats($enseignant)
    {
        $totalNotesSaisies = $enseignant->notes()->count();
        
        // Notes saisies ce mois
        $notesCeMois = $enseignant->notes()
            ->whereMonth('date_saisie', Carbon::now()->month)
            ->whereYear('date_saisie', Carbon::now()->year)
            ->count();
        
        // Nombre de matières enseignées
        $nbMatieres = $enseignant->affectations()
            ->where('annee_academique', '2025-2026')
            ->distinct('matiere_id')
            ->count();
        
        // Nombre d'étudiants suivis
        $nbEtudiants = $enseignant->notes()
            ->distinct('etudiant_id')
            ->count();
        
        // Moyenne des notes saisies
        $moyenneNotes = $enseignant->notes()->avg('valeur') ?? 0;
        
        // Taux de réussite
        $totalNotes = $enseignant->notes()->count();
        $notesReussies = $enseignant->notes()->where('valeur', '>=', 10)->count();
        $tauxReussite = $totalNotes > 0 ? round(($notesReussies / $totalNotes) * 100, 1) : 0;
        
        return [
            'total_notes' => $totalNotesSaisies,
            'notes_ce_mois' => $notesCeMois,
            'nb_matieres' => $nbMatieres,
            'nb_etudiants' => $nbEtudiants,
            'moyenne_notes' => round($moyenneNotes, 2),
            'taux_reussite' => $tauxReussite,
        ];
    }
    
    /**
     * Obtenir les statistiques par matière
     */
    private function getStatsParMatiere($enseignant)
    {
        return $enseignant->affectations()
            ->with('matiere')
            ->where('annee_academique', '2025-2026')
            ->get()
            ->map(function ($affectation) use ($enseignant) {
                $notes = Note::where('enseignant_id', $enseignant->id)
                    ->where('matiere_id', $affectation->matiere_id)
                    ->get();
                
                return [
                    'matiere' => $affectation->matiere,
                    'niveau' => $affectation->niveau,
                    'nb_notes' => $notes->count(),
                    'moyenne' => round($notes->avg('valeur') ?? 0, 2),
                    'taux_reussite' => $notes->count() > 0 
                        ? round(($notes->where('valeur', '>=', 10)->count() / $notes->count()) * 100, 1) 
                        : 0,
                ];
            });
    }
}