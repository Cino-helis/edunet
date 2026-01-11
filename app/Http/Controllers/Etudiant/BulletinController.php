<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BulletinController extends Controller
{
    /**
     * Afficher le bulletin de l'étudiant
     */
    public function index(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        // Récupérer l'inscription active
        $inscriptionActive = $etudiant->inscriptions()
            ->where('statut', 'en_cours')
            ->where('annee_academique', '2025-2026')
            ->with(['filiere', 'niveau'])
            ->first();
        
        // Années académiques disponibles
        $anneesAcademiques = $etudiant->inscriptions()
            ->select('annee_academique')
            ->distinct()
            ->pluck('annee_academique')
            ->sort()
            ->reverse()
            ->values();
        
        // Filtres
        $anneeSelectionnee = $request->get('annee_academique', '2025-2026');
        $semestreSelectionne = $request->get('semestre');
        
        // Récupérer les notes selon les filtres
        $query = $etudiant->notes()
            ->with(['matiere', 'enseignant'])
            ->where('annee_academique', $anneeSelectionnee);
        
        if ($semestreSelectionne) {
            $query->whereHas('matiere', function($q) use ($semestreSelectionne) {
                $q->where('semestre', $semestreSelectionne);
            });
        }
        
        $notes = $query->get();
        
        // Grouper les notes par matière
        $notesParMatiere = $notes->groupBy('matiere_id')->map(function ($notesMatiere) {
            $matiere = $notesMatiere->first()->matiere;
            
            // Calculer la moyenne de la matière
            $moyenne = $notesMatiere->avg('valeur');
            
            // Déterminer le statut
            $statut = $moyenne >= 10 ? 'Validé' : 'Non validé';
            $couleur = $moyenne >= 10 ? 'success' : 'danger';
            
            return [
                'matiere' => $matiere,
                'notes' => $notesMatiere,
                'moyenne' => round($moyenne, 2),
                'coefficient' => $matiere->coefficient,
                'credits' => $matiere->credits,
                'statut' => $statut,
                'couleur' => $couleur,
                'nb_notes' => $notesMatiere->count(),
            ];
        });
        
        // Calculer la moyenne générale pondérée
        $sommeNotesPonderees = $notesParMatiere->sum(function ($item) {
            return $item['moyenne'] * $item['coefficient'];
        });
        
        $sommeCoefficients = $notesParMatiere->sum(function ($item) {
            return $item['coefficient'];
        });
        
        $moyenneGenerale = $sommeCoefficients > 0 
            ? round($sommeNotesPonderees / $sommeCoefficients, 2)
            : 0;
        
        // Calculer le total de crédits - CORRECTION ICI
        $creditsObtenus = $notesParMatiere->filter(function($item) {
            return $item['moyenne'] >= 10;
        })->sum(function($item) {
            return $item['credits'];
        });
        
        $creditsTotaux = $notesParMatiere->sum(function($item) {
            return $item['credits'];
        });
        
        // Déterminer la mention
        $mention = $this->getMention($moyenneGenerale);
        
        // Calculer le taux de réussite
        $matieresReussies = $notesParMatiere->filter(function($item) {
            return $item['moyenne'] >= 10;
        })->count();
        
        $tauxReussite = $notesParMatiere->count() > 0 
            ? round(($matieresReussies / $notesParMatiere->count()) * 100, 1)
            : 0;
        
        // Statistiques globales
        $stats = [
            'moyenne_generale' => $moyenneGenerale,
            'credits_obtenus' => $creditsObtenus,
            'credits_totaux' => $creditsTotaux,
            'mention' => $mention,
            'taux_reussite' => $tauxReussite,
            'matieres_reussies' => $matieresReussies,
            'total_matieres' => $notesParMatiere->count(),
        ];
        
        return view('etudiant.bulletin.index', compact(
            'etudiant',
            'inscriptionActive',
            'notesParMatiere',
            'stats',
            'anneesAcademiques',
            'anneeSelectionnee',
            'semestreSelectionne'
        ));
    }
    
    /**
     * Télécharger le bulletin en PDF
     */
    public function downloadPDF(Request $request)
    {
        // TODO: Implémenter la génération de PDF
        return back()->with('info', 'Génération de PDF en cours de développement');
    }
    
    /**
     * Déterminer la mention selon la moyenne
     */
    private function getMention($moyenne)
    {
        if ($moyenne >= 16) return 'Très Bien';
        if ($moyenne >= 14) return 'Bien';
        if ($moyenne >= 12) return 'Assez Bien';
        if ($moyenne >= 10) return 'Passable';
        return 'Insuffisant';
    }
}