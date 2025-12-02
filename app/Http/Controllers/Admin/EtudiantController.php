<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
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
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email existe déjà',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
            'date_naissance.required' => 'La date de naissance est obligatoire',
            'lieu_naissance.required' => 'Le lieu de naissance est obligatoire',
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

            // Créer l'étudiant (sans matricule pour l'instant)
            $etudiant = Etudiant::create([
                'user_id' => $user->id,
                'matricule' => 'TEMP', // Temporaire, sera remplacé
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'date_naissance' => $validated['date_naissance'],
                'lieu_naissance' => $validated['lieu_naissance'],
            ]);

            // Générer le matricule basé sur l'ID
            $etudiant->matricule = 'ETU' . str_pad($etudiant->id, 5, '0', STR_PAD_LEFT);
            $etudiant->save();

            DB::commit();

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant créé avec succès ! Matricule : ' . $etudiant->matricule);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log l'erreur pour le débogage
            \Log::error('Erreur création étudiant: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'étudiant : ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $etudiant = Etudiant::with(['user', 'inscriptions.filiere', 'inscriptions.niveau', 'notes.matiere'])
            ->findOrFail($id);
        
        return view('admin.etudiants.show', compact('etudiant'));
    }

    public function edit($id)
    {
        $etudiant = Etudiant::with('user')->findOrFail($id);
        return view('admin.etudiants.edit', compact('etudiant'));
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $etudiant->user_id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'matricule' => 'required|string|unique:etudiants,matricule,' . $etudiant->id,
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'password' => 'nullable|min:8', // Optionnel lors de la mise à jour
        ]);

        DB::beginTransaction();
        try {
            // Mettre à jour l'utilisateur
            $userData = ['email' => $validated['email']];
            
            // Si un nouveau mot de passe est fourni
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            
            $etudiant->user->update($userData);

            // Mettre à jour l'étudiant
            $etudiant->update([
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'matricule' => $validated['matricule'],
                'date_naissance' => $validated['date_naissance'],
                'lieu_naissance' => $validated['lieu_naissance'],
            ]);

            DB::commit();

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant modifié avec succès !');

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
            $etudiant = Etudiant::findOrFail($id);
            $user = $etudiant->user;
            
            // Supprimer l'étudiant (cascade supprimera les relations)
            $etudiant->delete();
            
            // Supprimer l'utilisateur
            $user->delete();

            DB::commit();

            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant supprimé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}