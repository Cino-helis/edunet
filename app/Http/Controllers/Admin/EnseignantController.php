<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EnseignantController extends Controller
{
    public function index()
    {
        $enseignants = Enseignant::with('user')->latest()->paginate(15);
        return view('admin.enseignants.index', compact('enseignants'));
    }

    public function create()
    {
        return view('admin.enseignants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email existe déjà',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type_utilisateur' => 'enseignant',
                'email_verified_at' => now(),
            ]);

            Enseignant::create([
                'user_id' => $user->id,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'specialite' => $validated['specialite'],
                'departement' => $validated['departement'],
            ]);

            DB::commit();
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant créé avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Enseignant $enseignant)
    {
        $enseignant->load(['user', 'affectations.matiere', 'notes']);
        return view('admin.enseignants.show', compact('enseignant'));
    }

    public function edit(Enseignant $enseignant)
    {
        return view('admin.enseignants.edit', compact('enseignant'));
    }

    public function update(Request $request, Enseignant $enseignant)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $enseignant->user_id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $enseignant->user->update(['email' => $validated['email']]);
            
            if ($request->filled('password')) {
                $enseignant->user->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            $enseignant->update([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'specialite' => $validated['specialite'],
                'departement' => $validated['departement'],
            ]);

            DB::commit();
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant modifié avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la modification');
        }
    }

    public function destroy(Enseignant $enseignant)
    {
        try {
            $enseignant->user->delete();
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cet enseignant');
        }
    }
}