<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use App\Models\Filiere;
use Illuminate\Http\Request;

class NiveauController extends Controller
{
    public function index()
    {
        $niveaux = Niveau::with('filiere')->orderBy('ordre')->paginate(15);
        return view('admin.niveaux.index', compact('niveaux'));
    }

    public function create()
    {
        $filieres = Filiere::orderBy('nom')->get();
        return view('admin.niveaux.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'code' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'ordre' => 'required|integer|min:1',
            'credits_requis' => 'required|integer|min:0',
        ]);

        Niveau::create($validated);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau créé avec succès !');
    }

    public function show(Niveau $niveau)
    {
        $niveau->load(['filiere', 'inscriptions.etudiant']);
        return view('admin.niveaux.show', compact('niveau'));
    }

    public function edit(Niveau $niveau)
    {
        $filieres = Filiere::orderBy('nom')->get();
        return view('admin.niveaux.edit', compact('niveau', 'filieres'));
    }

    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'code' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'ordre' => 'required|integer|min:1',
            'credits_requis' => 'required|integer|min:0',
        ]);

        $niveau->update($validated);

        return redirect()->route('admin.niveaux.index')
            ->with('success', 'Niveau modifié avec succès !');
    }

    public function destroy(Niveau $niveau)
    {
        try {
            $niveau->delete();
            return redirect()->route('admin.niveaux.index')
                ->with('success', 'Niveau supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer ce niveau');
        }
    }
}