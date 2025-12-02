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
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        ]);

        DB::beginTransaction();
        try {
            // Créer l'utilisateur
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type_utilisateur' => 'enseignant',
                'email_verified_at' => now(),
            ]);

            // Créer l'enseignant
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
            
            \Log::error('Erreur création enseignant: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $enseignant = Enseignant::with(['user', 'affectations.matiere', 'affectations.niveau', 'notes'])
            ->findOrFail($id);
        
        return view('admin.enseignants.show', compact('enseignant'));
    }

    public function edit($id)
    {
        $enseignant = Enseignant::with('user')->findOrFail($id);
        return view('admin.enseignants.edit', compact('enseignant'));
    }

    public function update(Request $request, $id)
    {
        $enseignant = Enseignant::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $enseignant->user_id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'specialite' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'password' => 'nullable|min:8',
        ]);

        DB::beginTransaction();
        try {
            // Mettre à jour l'utilisateur
            $userData = ['email' => $validated['email']];
            
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $enseignant->user->update($userData);

            // Mettre à jour l'enseignant
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
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $enseignant = Enseignant::findOrFail($id);
            $user = $enseignant->user;
            
            $enseignant->delete();
            $user->delete();

            DB::commit();

            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant supprimé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}