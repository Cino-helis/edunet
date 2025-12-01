<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Inscription;
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

        // Détermination de l'année académique courante : peut être dynamique ou configurée
        // Ici, on part de l'année courante
        $anneeActuelle = Carbon::now()->year;
        $anneeAcademique = "{$anneeActuelle}-" . ($anneeActuelle + 1);

        // Récupère l'inscription active de l'étudiant pour l'année académique courante
        $inscriptionActive = $etudiant->inscriptions()
            ->where('annee_academique', $anneeAcademique)
            ->where('statut', 'actif')
            ->first();

        // Si pas d'inscription active, retourne vue avec un warning
        if (!$inscriptionActive) {
            return view('etudiant.matieres.index', [
                'inscriptionActive' => false,
                'matieres' => collect(),
                'anneeAcademique' => $anneeAcademique,
            ]);
        }

        // Récupère les matières liées au niveau et filière de l'inscription
        $niveau = $inscriptionActive->niveau;
        $matieres = $niveau
            ? $niveau->matieres()->wherePivot('filiere_id', $inscriptionActive->filiere_id)->get()
            : collect();

        return view('etudiant.matieres.index', [
            'inscriptionActive' => true,
            'inscription' => $inscriptionActive,
            'matieres' => $matieres,
            'anneeAcademique' => $anneeAcademique,
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

        // Vérifie que la matière appartient bien à l'inscription active
        $anneeActuelle = Carbon::now()->year;
        $anneeAcademique = "{$anneeActuelle}-" . ($anneeActuelle + 1);
        $inscriptionActive = $etudiant->inscriptions()
            ->where('annee_academique', $anneeAcademique)
            ->where('statut', 'actif')
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

        // On peut charger la note éventuelle pour cette matière pour l'étudiant
        $note = $etudiant->notes()->where('matiere_id', $matiere->id)->first();

        return view('etudiant.matieres.show', [
            'matiere' => $matiere,
            'note' => $note,
            'inscription' => $inscriptionActive,
        ]);
    }
}