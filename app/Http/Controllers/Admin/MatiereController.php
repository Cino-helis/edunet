<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matiere;
use Illuminate\Http\Request;

class MatiereController extends Controller
{
    public function index()
    {
        $matieres = Matiere::withCount('notes')->latest()->paginate(15);
        return view('admin.matieres.index', compact('matieres'));
    }

    public function create()
    {
        return view('admin.matieres.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:matieres,code',
            'nom' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:20',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'semestre' => 'required|integer|in:1,2',
            'type' => 'required|in:obligatoire,optionnelle',
        ]);

        Matiere::create($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière créée avec succès !');
    }

    public function edit(Matiere $matiere)
    {
        return view('admin.matieres.edit', compact('matiere'));
    }

    public function update(Request $request, Matiere $matiere)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:matieres,code,' . $matiere->id,
            'nom' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:20',
            'coefficient' => 'required|numeric|min:0.5|max:10',
            'semestre' => 'required|integer|in:1,2',
            'type' => 'required|in:obligatoire,optionnelle',
        ]);

        $matiere->update($validated);

        return redirect()->route('admin.matieres.index')
            ->with('success', 'Matière modifiée avec succès !');
    }

    public function destroy(Matiere $matiere)
    {
        try {
            $matiere->delete();
            return redirect()->route('admin.matieres.index')
                ->with('success', 'Matière supprimée avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cette matière');
        }
    }
}