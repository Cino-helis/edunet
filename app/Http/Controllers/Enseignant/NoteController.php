<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Etudiant;
use App\Models\Matiere;
use App\Models\Niveau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $enseignant = auth()->user()->enseignant;
        
        $query = Note::where('enseignant_id', $enseignant->id)
            ->with(['etudiant', 'matiere']);
        
        // Filtres
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }
        
        if ($request->filled('type_evaluation')) {
            $query->where('type_evaluation', $request->type_evaluation);
        }
        
        $notes = $query->latest('date_saisie')->paginate(20);
        
        // Matières de l'enseignant
        $matieres = $enseignant->matieres()->distinct()->get();
        
        return view('enseignant.notes.index', compact('notes', 'matieres'));
    }
    
    public function create()
    {
        $enseignant = auth()->user()->enseignant;
        
        // Récupérer les matières assignées à l'enseignant
        $affectations = $enseignant->affectations()
            ->with(['matiere', 'niveau'])
            ->where('annee_academique', '2024-2025')
            ->get();
        
        return view('enseignant.notes.create', compact('affectations'));
    }
    
    public function store(Request $request)
    {
        $enseignant = auth()->user()->enseignant;
        
        $validated = $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'matiere_id' => 'required|exists:matieres,id',
            'valeur' => 'required|numeric|min:0|max:20',
            'type_evaluation' => 'required|in:CC,TP,Examen,Projet',
            'annee_academique' => 'required|string',
        ]);
        
        $validated['enseignant_id'] = $enseignant->id;
        $validated['date_saisie'] = now();
        
        Note::create($validated);
        
        return redirect()->route('enseignant.notes.index')
            ->with('success', 'Note ajoutée avec succès !');
    }
    
    /**
     * Saisie groupée de notes
     */
    public function saisieGroupee()
    {
        $enseignant = auth()->user()->enseignant;
        
        $affectations = $enseignant->affectations()
            ->with(['matiere', 'niveau.filiere'])
            ->where('annee_academique', '2024-2025')
            ->get();
        
        return view('enseignant.notes.saisie-groupee', compact('affectations'));
    }
    
    /**
     * Obtenir les étudiants d'un niveau (AJAX)
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
        $enseignant = auth()->user()->enseignant;
        
        $validated = $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
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
                        'enseignant_id' => $enseignant->id,
                        'valeur' => $noteData['valeur'],
                        'date_saisie' => now(),
                    ]
                );
            }
            
            DB::commit();
            
            return redirect()->route('enseignant.notes.index')
                ->with('success', count($validated['notes']) . ' notes enregistrées avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'enregistrement des notes');
        }
    }
}