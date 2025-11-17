<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etudiant;
use App\Models\User;
use App\Models\Filiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EtudiantController extends Controller
{
    public function index()
    {
        $etudiants = Etudiant::with('user')->latest()->paginate(15);
        return view('admin.etudiants.index', compact('etudiants'));
    }

    public function create()
    {
        $filieres = Filiere::all();
        return view('admin.etudiants.create', compact('filieres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'matricule' => 'required|string|unique:etudiants,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email existe déjà',
            'matricule.unique' => 'Ce matricule existe déjà',
        ]);

        DB::beginTransaction();
        try {
            // Créer l'utilisateur
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type_utilisateur' => 'etudiant',
                'email_verified_at' => now(),
            ]);

            // Créer l'étudiant
            Etudiant::create([
                'user_id' => $user->id,
                'matricule' => $validated['matricule'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'date_naissance' => $validated['date_naissance'],
                'lieu_naissance' => $validated['lieu_naissance'],
            ]);

            DB::commit();

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant créé avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function show(Etudiant $etudiant)
    {
        $etudiant->load(['user', 'inscriptions.filiere', 'notes.matiere']);
        return view('admin.etudiants.show', compact('etudiant'));
    }

    public function edit(Etudiant $etudiant)
    {
        return view('admin.etudiants.edit', compact('etudiant'));
    }

    public function update(Request $request, Etudiant $etudiant)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $etudiant->user_id,
            'matricule' => 'required|string|unique:etudiants,matricule,' . $etudiant->id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Mettre à jour l'utilisateur
            $etudiant->user->update([
                'email' => $validated['email'],
            ]);

            // Mettre à jour l'étudiant
            $etudiant->update([
                'matricule' => $validated['matricule'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'date_naissance' => $validated['date_naissance'],
                'lieu_naissance' => $validated['lieu_naissance'],
            ]);

            // Mettre à jour le mot de passe si fourni
            if ($request->filled('password')) {
                $etudiant->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant modifié avec succès !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la modification');
        }
    }

    public function destroy(Etudiant $etudiant)
    {
        try {
            $etudiant->user->delete(); // Cascade delete
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant supprimé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de supprimer cet étudiant');
        }
    }
}