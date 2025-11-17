<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Note::with(['etudiant', 'matiere', 'enseignant']);

        // Filtres
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('etudiant_id')) {
            $query->where('etudiant_id', $request->etudiant_id);
        }

        if ($request->filled('type_evaluation')) {
            $query->where('type_evaluation', $request->type_evaluation);
        }

        if ($request->filled('annee_academique')) {
            $query->where('annee_academique', $request->annee_academique);
        }

        $notes = $query->latest('date_saisie')->paginate(20);
        $matieres = Matiere::orderBy('nom')->get();
        $etudiants = Etudiant::orderBy('nom')->get();

        return view('admin.notes.index', compact('notes', 'matieres', 'etudiants'));
    }

    public function create()
    {
        $matieres = Matiere::orderBy('nom')->get();
        $etudiants = Etudiant::with('user')->orderBy('nom')->get();
        $enseignants = Enseignant::with('user')->orderBy('nom')->get();
        
        return view('admin.notes.create', compact('matieres', 'etudiants', 'enseignants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'valeur' => 'required|numeric|min:0|max:20',
            'type_evaluation' => 'required|in:CC,TP,Examen,Projet',
            'annee_academique' => 'required|string',
        ], [
            'valeur.min' => 'La note doit être au minimum 0',
            'valeur.max' => 'La note doit être au maximum 20',
        ]);

        $validated['date_saisie'] = now();

        Note::create($validated);

        return redirect()->route('admin.notes.index')
            ->with('success', 'Note ajoutée avec succès !');
    }

    public function edit(Note $note)
    {
        $matieres = Matiere::orderBy('nom')->get();
        $etudiants = Etudiant::with('user')->orderBy('nom')->get();
        $enseignants = Enseignant::with('user')->orderBy('nom')->get();
        
        return view('admin.notes.edit', compact('note', 'matieres', 'etudiants', 'enseignants'));
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'valeur' => 'required|numeric|min:0|max:20',
            'type_evaluation' => 'required|in:CC,TP,Examen,Projet',
            'annee_academique' => 'required|string',
        ]);

        $note->update($validated);

        return redirect()->route('admin.notes.index')
            ->with('success', 'Note modifiée avec succès !');
    }

    public function destroy(Note $note)
    {
        try {
            $note->delete();
            return redirect()->route('admin.notes.index')
                ->with('success', 'Note supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cette note');
        }
    }

    /**
     * Saisie groupée de notes pour une matière
     */
    public function saisieGroupee()
    {
        $matieres = Matiere::orderBy('nom')->get();
        $niveaux = Niveau::with('filiere')->orderBy('ordre')->get();
        
        return view('admin.notes.saisie-groupee', compact('matieres', 'niveaux'));
    }

    /**
     * Obtenir les étudiants par niveau (AJAX)
     */
    public function getEtudiantsByNiveau(Request $request)
    {
        $niveauId = $request->niveau_id;
        $etudiants = Etudiant::whereHas('inscriptions', function($query) use ($niveauId) {
            $query->where('niveau_id', $niveauId)
                  ->where('statut', 'en_cours');
        })->with('user')->orderBy('nom')->get();

        return response()->json($etudiants);
    }

    /**
     * Enregistrer les notes en masse
     */
    public function storeSaisieGroupee(Request $request)
    {
        $validated = $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'enseignant_id' => 'required|exists:enseignants,id',
            'type_evaluation' => 'required|in:CC,TP,Examen,Projet',
            'annee_academique' => 'required|string',
            'notes' => 'required|array',
            'notes.*.etudiant_id' => 'required|exists:etudiants,id',
            'notes.*.valeur' => 'required|numeric|min:0|max:20',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['notes'] as $noteData) {
                Note::updateOrCreate(
                    [
                        'etudiant_id' => $noteData['etudiant_id'],
                        'matiere_id' => $validated['matiere_id'],
                        'type_evaluation' => $validated['type_evaluation'],
                        'annee_academique' => $validated['annee_academique'],
                    ],
                    [
                        'enseignant_id' => $validated['enseignant_id'],
                        'valeur' => $noteData['valeur'],
                        'date_saisie' => now(),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.notes.index')
                ->with('success', count($validated['notes']) . ' notes enregistrées avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'enregistrement des notes');
        }
    }

    /**
     * Calculer les moyennes d'un étudiant
     */
    public function calculerMoyennes(Etudiant $etudiant)
    {
        $notes = $etudiant->notes()->with('matiere')->get();
        
        // Grouper par matière
        $notesParMatiere = $notes->groupBy('matiere_id')->map(function ($notesMatiere) {
            $matiere = $notesMatiere->first()->matiere;
            $moyenne = $notesMatiere->avg('valeur');
            
            return [
                'matiere' => $matiere,
                'notes' => $notesMatiere,
                'moyenne' => round($moyenne, 2),
                'coefficient' => $matiere->coefficient,
                'credits' => $matiere->credits,
            ];
        });

        // Calculer la moyenne générale
        $sommeNotesPonderees = $notesParMatiere->sum(function ($item) {
            return $item['moyenne'] * $item['coefficient'];
        });
        
        $sommeCoefficients = $notesParMatiere->sum('coefficient');
        
        $moyenneGenerale = $sommeCoefficients > 0 
            ? round($sommeNotesPonderees / $sommeCoefficients, 2)
            : 0;

        return view('admin.notes.moyennes', compact('etudiant', 'notesParMatiere', 'moyenneGenerale'));
    }
}