<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Affectation;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class AffectationController extends Controller
{
    public function index()
    {
        $affectations = Affectation::with(['enseignant', 'filiere', 'niveau', 'matiere'])
            ->latest()
            ->paginate(15);
        
        return view('admin.affectations.index', compact('affectations'));
    }

    public function create()
    {
        $enseignants = Enseignant::with('user')->orderBy('nom')->get();
        $filieres = Filiere::orderBy('nom')->get();
        
        return view('admin.affectations.create', compact('enseignants', 'filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'enseignants' => 'required|array',
            'enseignants.*' => 'exists:enseignants,id',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'matieres' => 'required|array',
            'matieres.*' => 'exists:matieres,id',
            'annee_academique' => 'required|string',
        ]);

        $validated['date_affectation'] = now();
        
        $count = 0;
        foreach ($validated['enseignants'] as $enseignantId) {
            foreach ($validated['matieres'] as $matiereId) {
                // Vérifier si l'affectation existe déjà
                $exists = Affectation::where('enseignant_id', $enseignantId)
                    ->where('filiere_id', $validated['filiere_id'])
                    ->where('niveau_id', $validated['niveau_id'])
                    ->where('matiere_id', $matiereId)
                    ->where('annee_academique', $validated['annee_academique'])
                    ->exists();

                if (!$exists) {
                    Affectation::create([
                        'enseignant_id' => $enseignantId,
                        'filiere_id' => $validated['filiere_id'],
                        'niveau_id' => $validated['niveau_id'],
                        'matiere_id' => $matiereId,
                        'annee_academique' => $validated['annee_academique'],
                        'date_affectation' => $validated['date_affectation'],
                    ]);
                    $count++;
                }
            }
        }

        return redirect()->route('admin.affectations.index')
            ->with('success', "$count affectation(s) créée(s) avec succès !");
    }

    public function destroy(Affectation $affectation)
    {
        try {
            $affectation->delete();
            return redirect()->route('admin.affectations.index')
                ->with('success', 'Affectation supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cette affectation');
        }
    }

    // API : Récupérer les matières d'un niveau
    public function getMatieresByNiveau(Request $request)
    {
        $niveauId = $request->niveau_id;
        $filiereId = $request->filiere_id;
        
        $matieres = \DB::table('filiere_matiere_niveau')
            ->join('matieres', 'filiere_matiere_niveau.matiere_id', '=', 'matieres.id')
            ->where('filiere_matiere_niveau.filiere_id', $filiereId)
            ->where('filiere_matiere_niveau.niveau_id', $niveauId)
            ->select('matieres.*')
            ->get();
        
        return response()->json($matieres);
    }
}