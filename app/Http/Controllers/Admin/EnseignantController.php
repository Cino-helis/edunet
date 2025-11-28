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
            // ... autres champs
        ]);

        // Envoyer l'email avec les identifiants
        $user->notify(new \App\Notifications\AccountCreatedNotification(
            $temporaryPassword, 
            'étudiant'
        ));

        DB::commit();

        return redirect()->route('admin.etudiants.index')
            ->with('success', 'Étudiant créé avec succès ! Un email contenant les identifiants a été envoyé.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
    }
}
}