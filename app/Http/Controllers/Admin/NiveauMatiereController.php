<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Niveau;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NiveauMatiereController extends Controller
{
    public function edit(Niveau $niveau)
    {
        $niveau->load('filiere', 'matieres');
        $matieres = Matiere::orderBy('nom')->get();
        
        return view('admin.niveaux.matieres', compact('niveau', 'matieres'));
    }

    public function update(Request $request, Niveau $niveau)
    {
        $validated = $request->validate([
            'matieres' => 'required|array',
            'matieres.*' => 'exists:matieres,id',
        ]);

        DB::beginTransaction();
        try {
            // Supprimer les anciennes associations
            DB::table('filiere_matiere_niveau')
                ->where('filiere_id', $niveau->filiere_id)
                ->where('niveau_id', $niveau->id)
                ->delete();

            // Créer les nouvelles associations
            foreach ($validated['matieres'] as $matiereId) {
                DB::table('filiere_matiere_niveau')->insert([
                    'filiere_id' => $niveau->filiere_id,
                    'niveau_id' => $niveau->id,
                    'matiere_id' => $matiereId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.niveaux.index')
                ->with('success', 'Matières affectées avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'affectation des matières');
        }
    }
}