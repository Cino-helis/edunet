<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Filiere;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistiqueController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_etudiants' => Etudiant::count(),
            'total_enseignants' => Enseignant::count(),
            'total_filieres' => Filiere::count(),
            'total_matieres' => Matiere::count(),
            'total_notes' => Note::count(),
            'moyenne_generale' => round(Note::avg('valeur'), 2),
            'taux_reussite' => Note::count() > 0 
                ? round((Note::where('valeur', '>=', 10)->count() / Note::count()) * 100, 1) 
                : 0,
        ];

        // Évolution des inscriptions (6 derniers mois)
        $evolutionInscriptions = Etudiant::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as mois'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subMonths(6))
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

        // Top 5 meilleures matières
        $topMatieres = Matiere::withAvg('notes', 'valeur')
            ->having('notes_avg_valeur', '>', 0)
            ->orderBy('notes_avg_valeur', 'desc')
            ->take(5)
            ->get();

        // Top 5 pires matières
        $worstMatieres = Matiere::withAvg('notes', 'valeur')
            ->having('notes_avg_valeur', '>', 0)
            ->orderBy('notes_avg_valeur', 'asc')
            ->take(5)
            ->get();

        // Répartition des notes par tranche
        $repartitionNotes = [
            'excellent' => Note::whereBetween('valeur', [16, 20])->count(),
            'tres_bien' => Note::whereBetween('valeur', [14, 15.99])->count(),
            'bien' => Note::whereBetween('valeur', [12, 13.99])->count(),
            'assez_bien' => Note::whereBetween('valeur', [10, 11.99])->count(),
            'passable' => Note::whereBetween('valeur', [8, 9.99])->count(),
            'insuffisant' => Note::where('valeur', '<', 8)->count(),
        ];

        // Notes par type d'évaluation
        $notesParType = Note::select('type_evaluation', DB::raw('COUNT(*) as total'), DB::raw('AVG(valeur) as moyenne'))
            ->groupBy('type_evaluation')
            ->get();

        // Top 10 étudiants
        $topEtudiants = Etudiant::withAvg('notes', 'valeur')
            ->having('notes_avg_valeur', '>', 0)
            ->orderBy('notes_avg_valeur', 'desc')
            ->take(10)
            ->get();

        return view('admin.statistiques', compact(
            'stats',
            'evolutionInscriptions',
            'topMatieres',
            'worstMatieres',
            'repartitionNotes',
            'notesParType',
            'topEtudiants'
        ));
    }

    /**
     * Exporter les statistiques en PDF
     */
    public function export()
    {
        // TODO: Implémenter l'export PDF
        return back()->with('info', 'Export PDF en cours de développement');
    }
}