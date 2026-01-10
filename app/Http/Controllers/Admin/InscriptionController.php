<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use Illuminate\Http\Request;

class InscriptionController extends Controller
{
    public function index()
    {
        $inscriptions = Inscription::with(['etudiant', 'filiere', 'niveau'])
            ->latest()
            ->paginate(15);
        
        return view('admin.inscriptions.index', compact('inscriptions'));
    }

    public function create()
    {
        $etudiants = Etudiant::with('user')
            ->whereDoesntHave('inscriptions', function($query) {
                $query->where('annee_academique', '2025-2026')
                      ->where('statut', 'en_cours');
            })
            ->orderBy('nom')
            ->get();
        
        $filieres = Filiere::orderBy('nom')->get();
        
        return view('admin.inscriptions.create', compact('etudiants', 'filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'etudiants' => 'required|array',
            'etudiants.*' => 'exists:etudiants,id',
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'annee_academique' => 'required|string',
            'statut' => 'required|in:en_cours,validee,suspendue,abandonnee',
        ]);

        $validated['date_inscription'] = now();
        
        $count = 0;
        foreach ($validated['etudiants'] as $etudiantId) {
            // Vérifier si l'inscription existe déjà
            $exists = Inscription::where('etudiant_id', $etudiantId)
                ->where('niveau_id', $validated['niveau_id'])
                ->where('annee_academique', $validated['annee_academique'])
                ->exists();
            
            if (!$exists) {
                Inscription::create([
                    'etudiant_id' => $etudiantId,
                    'filiere_id' => $validated['filiere_id'],
                    'niveau_id' => $validated['niveau_id'],
                    'annee_academique' => $validated['annee_academique'],
                    'statut' => $validated['statut'],
                    'date_inscription' => $validated['date_inscription'],
                ]);
                $count++;
            }
        }

        return redirect()->route('admin.inscriptions.index')
            ->with('success', "$count inscription(s) créée(s) avec succès !");
    }

    public function edit(Inscription $inscription)
    {
        $filieres = Filiere::orderBy('nom')->get();
        $niveaux = Niveau::where('filiere_id', $inscription->filiere_id)->get();
        
        return view('admin.inscriptions.edit', compact('inscription', 'filieres', 'niveaux'));
    }

    public function update(Request $request, Inscription $inscription)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'niveau_id' => 'required|exists:niveaux,id',
            'annee_academique' => 'required|string',
            'statut' => 'required|in:en_cours,validee,suspendue,abandonnee',
        ]);

        $inscription->update($validated);

        return redirect()->route('admin.inscriptions.index')
            ->with('success', 'Inscription modifiée avec succès !');
    }

    public function destroy(Inscription $inscription)
    {
        try {
            $inscription->delete();
            return redirect()->route('admin.inscriptions.index')
                ->with('success', 'Inscription supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cette inscription');
        }
    }

    // API : Récupérer les niveaux d'une filière
    public function getNiveauxByFiliere(Request $request)
    {
        $filiereId = $request->filiere_id;
        $niveaux = Niveau::where('filiere_id', $filiereId)->orderBy('ordre')->get();
        return response()->json($niveaux);
    }
}