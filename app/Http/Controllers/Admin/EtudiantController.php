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
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
        ], [
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email existe déjà',
        ]);

        DB::beginTransaction();
        try {
            // Générer un mot de passe aléatoire
        $temporaryPassword = Str::random(12);
        
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($temporaryPassword),
            'type_utilisateur' => 'etudiant', // ou 'enseignant'
            'email_verified_at' => now(),
        ]);

        $etudiant = Etudiant::create([
            'user_id' => $user->id,
            // 'matricule' => $validated['matricule'] ?? null,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'date_naissance' => $validated['date_naissance'] ?? null,
                'lieu_naissance' => $validated['lieu_naissance'] ?? null,
        ]);

        // Génération du matricule
        $etudiant->matricule = 'ETU' . str_pad($etudiant->id, 5, '0', STR_PAD_LEFT);
        $etudiant->save();

        DB::commit();

        // Tu peux ici informer le mot de passe temporaire (ne pas le réutiliser en clair en prod !)
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant créé avec succès, matricule : ' . $etudiant->matricule);

        } catch (\Exception $e) {
            DB::rollBack();
            // Affiche l’erreur côté vue si besoin
            return back()->withInput()->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        return view('admin.etudiants.edit', compact('etudiant'));
    }

    public function update(Request $request, $id)
    {
        $etudiant = Etudiant::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            //'matricule' => 'nullable|string|unique:etudiants,matricule,' . $etudiant->id,
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string',
            // autres
        ]);

        // Mise à jour du profil étudiant
        $etudiant->update($validated);

        return redirect()->route('admin.etudiants.index')->with('success', 'Étudiant modifié !');
    }

    public function destroy($id)
    {
        $etudiant = Etudiant::findOrFail($id);
        // Tu peux aussi supprimer le user associé ici si tu le souhaites
        $etudiant->delete();

        return redirect()->route('admin.etudiants.index')->with('success', 'Étudiant supprimé !');
    }
}