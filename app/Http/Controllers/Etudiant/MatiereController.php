<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Inscription;
use App\Models\Note;
use App\Models\Niveau;
use App\Models\EmploiTemps;
use App\Models\Enseignant;
use App\Models\Affectation;
use App\Models\Filiere;
use App\Models\Resultat;
use App\Models\User;
use Carbon\Carbon;

class MatiereController extends Controller
{
    /**
     * Affiche les matières de l'étudiant pour son inscription active
     */
    public function index()
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            abort(403, 'Aucun profil étudiant associé.');
        }

        // 🔍 DEBUG : Vérifier les données
        \Log::info('DEBUG MATIERES', [
            'etudiant_id' => $etudiant->id,
            'etudiant_nom' => $etudiant->nom_complet,
            'nb_inscriptions_total' => $etudiant->inscriptions()->count(),
            'inscriptions' => $etudiant->inscriptions()->get()->map(function($i) {
                return [
                    'id' => $i->id,
                    'filiere' => $i->filiere->nom,
                    'niveau' => $i->niveau->nom,
                    'annee_academique' => $i->annee_academique,
                    'statut' => $i->statut,
                ];
            })->toArray(),
        ]);

        // ✅ SOLUTION : Récupérer l'inscription active (en_cours ou validee)
        $inscriptionActive = $etudiant->inscriptions()
            ->whereIn('statut', ['en_cours', 'validee'])
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Si une inscription active existe, utiliser son année académique
        $anneeAcademique = $inscriptionActive ? $inscriptionActive->annee_academique : '2024-2025';

        // Si pas d'inscription active, retourne vue avec un warning
        if (!$inscriptionActive) {
            return view('etudiant.matieres.index', [
                'inscriptionActive' => false,
                'matieres' => collect(),
                'anneeAcademique' => $anneeAcademique,
                'matieresAvecStats' => collect(),
                'stats' => null,
                'semestreFiltre' => request('semestre'),
                'typeFiltre' => request('type'),
            ]);
        }

        // Récupère les matières liées au niveau et filière de l'inscription
        $niveau = $inscriptionActive->niveau;
        $matieres = $niveau
            ? $niveau->matieres()->wherePivot('filiere_id', $inscriptionActive->filiere_id)->get()
            : collect();

        // ✅ AJOUT : Appliquer les filtres
        $query = $matieres;
        
        if (request('semestre')) {
            $query = $query->where('semestre', request('semestre'));
        }
        
        if (request('type')) {
            $query = $query->where('type', request('type'));
        }

        // ✅ AJOUT : Enrichir avec les statistiques
        $matieresAvecStats = $query->map(function ($matiere) use ($etudiant, $anneeAcademique) {
            $notes = $etudiant->notes()
                ->where('matiere_id', $matiere->id)
                ->where('annee_academique', $anneeAcademique)
                ->get();
            
            $moyenne = $notes->count() > 0 ? $notes->avg('valeur') : null;
            
            // Récupérer l'enseignant de cette matière
            $enseignant = \App\Models\Enseignant::whereHas('affectation', function($query) use ($matiere, $inscriptionActive, $anneeAcademique) {
                $query->where('matiere_id', $matiere->id)
                    ->where('niveau_id', $inscriptionActive->niveau_id)
                    ->where('annee_academique', $anneeAcademique);
            })->first();
            
            return [
                'matiere' => $matiere,
                'moyenne' => $moyenne,
                'nb_notes' => $notes->count(),
                'statut' => $moyenne !== null ? ($moyenne >= 10 ? 'Validé' : 'Non validé') : 'En cours',
                'enseignant' => $enseignant,
            ];
        });

        // ✅ AJOUT : Calculer les statistiques globales
        $stats = [
            'total_matieres' => $matieres->count(),
            'matieres_validees' => $matieresAvecStats->filter(fn($m) => $m['moyenne'] !== null && $m['moyenne'] >= 10)->count(),
            'moyenne_generale' => $matieresAvecStats->where('moyenne', '!==', null)->avg('moyenne'),
            'credits_obtenus' => $matieresAvecStats->filter(fn($m) => $m['moyenne'] !== null && $m['moyenne'] >= 10)->sum(fn($m) => $m['matiere']->credits),
            'total_credits' => $matieres->sum('credits'),
        ];

        return view('etudiant.matieres.index', [
            'inscriptionActive' => true,
            'inscription' => $inscriptionActive,
            'matieres' => $matieres,
            'anneeAcademique' => $anneeAcademique,
            'matieresAvecStats' => $matieresAvecStats,
            'stats' => $stats,
            'semestreFiltre' => request('semestre'),
            'typeFiltre' => request('type'),
        ]);
    }

    /**
     * Affiche le détail d'une matière pour l'étudiant
     */
    public function show($id)
    {
        $user = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            abort(403, 'Aucun profil étudiant associé.');
        }

        $matiere = Matiere::findOrFail($id);

        // ✅ CORRECTION : Même logique pour le calcul de l'année
        $anneeActuelle = Carbon::now()->year;
        $moisActuel = Carbon::now()->month;
        
        if ($moisActuel < 9) {
            $anneeAcademique = ($anneeActuelle - 1) . "-" . $anneeActuelle;
        } else {
            $anneeAcademique = $anneeActuelle . "-" . ($anneeActuelle + 1);
        }

        $inscriptionActive = $etudiant->inscriptions()
            ->where('annee_academique', $anneeAcademique)
            ->where('statut', 'en_cours')
            ->first();

        $accessible = false;
        if ($inscriptionActive && $inscriptionActive->niveau) {
            $accessible = $inscriptionActive->niveau->matieres()
                ->wherePivot('filiere_id', $inscriptionActive->filiere_id)
                ->where('matieres.id', $matiere->id)
                ->exists();
        }

        if (!$accessible) {
            abort(403, "Cette matière n'est pas associée à votre inscription actuelle.");
        }

        // Récupérer les notes de l'étudiant pour cette matière
        $notes = $etudiant->notes()
            ->where('matiere_id', $matiere->id)
            ->where('annee_academique', $anneeAcademique)
            ->with('enseignant')
            ->orderBy('date_saisie', 'desc')
            ->get();

        // Statistiques
        $stats = [
            'moyenne' => $notes->count() > 0 ? $notes->avg('valeur') : null,
            'nb_notes' => $notes->count(),
            'meilleure_note' => $notes->count() > 0 ? $notes->max('valeur') : 0,
            'pire_note' => $notes->count() > 0 ? $notes->min('valeur') : 0,
            'statut' => $notes->count() > 0 && $notes->avg('valeur') >= 10 ? 'Validé' : 'Non validé',
        ];

        // Récupérer l'enseignant
        $enseignant = \App\Models\Enseignant::whereHas('affectations', function($query) use ($matiere, $inscriptionActive, $anneeAcademique) {
            $query->where('matiere_id', $matiere->id)
                ->where('niveau_id', $inscriptionActive->niveau_id)
                ->where('annee_academique', $anneeAcademique);
        })->first();

        return view('etudiant.matieres.show', [
            'matiere' => $matiere,
            'notes' => $notes,
            'inscription' => $inscriptionActive,
            'stats' => $stats,
            'enseignant' => $enseignant,
        ]);
    }
}