<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NoteController extends Controller
{
    /**
     * Afficher toutes les notes de l'étudiant avec filtres
     */
    public function index(Request $request)
    {
        $etudiant = auth()->user()->etudiant;
        
        // Récupérer les notes avec relations
        $query = $etudiant->notes()
            ->with(['matiere', 'enseignant']);
        
        // Filtres
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }
        
        if ($request->filled('type_evaluation')) {
            $query->where('type_evaluation', $request->type_evaluation);
        }
        
        if ($request->filled('annee_academique')) {
            $query->where('annee_academique', $request->annee_academique);
        }
        
        if ($request->filled('semestre')) {
            $query->whereHas('matiere', function($q) use ($request) {
                $q->where('semestre', $request->semestre);
            });
        }
        
        // Filtre par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'mois':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonth());
                    break;
                case 'trimestre':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonths(3));
                    break;
                case 'semestre':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonths(6));
                    break;
                case 'annee':
                    $query->where('date_saisie', '>=', Carbon::now()->subYear());
                    break;
            }
        }
        
        // Tri
        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'date':
                $query->orderBy('date_saisie', $sortOrder);
                break;
            case 'note':
                $query->orderBy('valeur', $sortOrder);
                break;
            case 'matiere':
                $query->join('matieres', 'notes.matiere_id', '=', 'matieres.id')
                      ->orderBy('matieres.nom', $sortOrder)
                      ->select('notes.*');
                break;
        }
        
        $notes = $query->paginate(20);
        
        // Statistiques globales
        $statsGlobales = $this->getStatsGlobales($etudiant, $request);
        
        // Matières pour le filtre
        $matieres = $etudiant->notes()
            ->with('matiere')
            ->get()
            ->pluck('matiere')
            ->unique('id')
            ->sortBy('nom');
        
        // Années académiques disponibles
        $anneesAcademiques = $etudiant->notes()
            ->select('annee_academique')
            ->distinct()
            ->pluck('annee_academique')
            ->sort()
            ->values();
        
        return view('etudiant.notes.index', compact(
            'notes',
            'statsGlobales',
            'matieres',
            'anneesAcademiques'
        ));
    }
    
    /**
     * Obtenir les statistiques globales avec filtres appliqués
     */
    private function getStatsGlobales($etudiant, $request)
    {
        $query = $etudiant->notes();
        
        // Appliquer les mêmes filtres
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }
        
        if ($request->filled('type_evaluation')) {
            $query->where('type_evaluation', $request->type_evaluation);
        }
        
        if ($request->filled('annee_academique')) {
            $query->where('annee_academique', $request->annee_academique);
        }
        
        if ($request->filled('semestre')) {
            $query->whereHas('matiere', function($q) use ($request) {
                $q->where('semestre', $request->semestre);
            });
        }
        
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'mois':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonth());
                    break;
                case 'trimestre':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonths(3));
                    break;
                case 'semestre':
                    $query->where('date_saisie', '>=', Carbon::now()->subMonths(6));
                    break;
                case 'annee':
                    $query->where('date_saisie', '>=', Carbon::now()->subYear());
                    break;
            }
        }
        
        $notes = $query->get();
        $totalNotes = $notes->count();
        
        if ($totalNotes === 0) {
            return [
                'total' => 0,
                'moyenne' => 0,
                'meilleure' => 0,
                'pire' => 0,
                'reussies' => 0,
                'echouees' => 0,
                'taux_reussite' => 0,
            ];
        }
        
        $notesReussies = $notes->where('valeur', '>=', 10)->count();
        
        return [
            'total' => $totalNotes,
            'moyenne' => round($notes->avg('valeur'), 2),
            'meilleure' => round($notes->max('valeur'), 2),
            'pire' => round($notes->min('valeur'), 2),
            'reussies' => $notesReussies,
            'echouees' => $totalNotes - $notesReussies,
            'taux_reussite' => round(($notesReussies / $totalNotes) * 100, 1),
        ];
    }
    
    /**
     * Afficher le détail d'une note
     */
    public function show($id)
    {
        $etudiant = auth()->user()->etudiant;
        
        $note = $etudiant->notes()
            ->with(['matiere', 'enseignant'])
            ->findOrFail($id);
        
        // Statistiques de la matière
        $statsMatiere = $etudiant->notes()
            ->where('matiere_id', $note->matiere_id)
            ->get();
        
        $stats = [
            'moyenne_matiere' => round($statsMatiere->avg('valeur'), 2),
            'nb_notes_matiere' => $statsMatiere->count(),
            'meilleure_note' => round($statsMatiere->max('valeur'), 2),
            'pire_note' => round($statsMatiere->min('valeur'), 2),
        ];
        
        return view('etudiant.notes.show', compact('note', 'stats'));
    }
}