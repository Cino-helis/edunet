<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use Illuminate\Http\Request;

class FiliereController extends Controller
{
    public function index()
    {
        $filieres = Filiere::withCount('niveaux', 'inscriptions')->latest()->paginate(10);
        return view('admin.filieres.index', compact('filieres'));
    }

    public function create()
    {
        return view('admin.filieres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:filieres,code',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_annees' => 'required|integer|min:1|max:10',
        ], [
            'code.required' => 'Le code est obligatoire',
            'code.unique' => 'Ce code existe déjà',
            'nom.required' => 'Le nom est obligatoire',
            'duree_annees.required' => 'La durée est obligatoire',
        ]);

        Filiere::create($validated);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière créée avec succès !');
    }

    public function show(Filiere $filiere)
    {
        $filiere->load(['niveaux', 'inscriptions.etudiant']);
        return view('admin.filieres.show', compact('filiere'));
    }

    public function edit(Filiere $filiere)
    {
        return view('admin.filieres.edit', compact('filiere'));
    }

    public function update(Request $request, Filiere $filiere)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:filieres,code,' . $filiere->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duree_annees' => 'required|integer|min:1|max:10',
        ]);

        $filiere->update($validated);

        return redirect()->route('admin.filieres.index')
            ->with('success', 'Filière modifiée avec succès !');
    }

    public function destroy(Filiere $filiere)
    {
        try {
            $filiere->delete();
            return redirect()->route('admin.filieres.index')
                ->with('success', 'Filière supprimée avec succès !');
        } catch (\Exception $e) {
            return redirect()->route('admin.filieres.index')
                ->with('error', 'Impossible de supprimer cette filière car elle contient des données associées.');
        }
    }
}