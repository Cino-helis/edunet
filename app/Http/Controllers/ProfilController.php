<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    /**
     * Afficher le profil de l'utilisateur connecté
     */
    public function index()
    {
        $user = auth()->user();
        $profil = $user->profil();
        
        return view('profil.index', compact('user', 'profil'));
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function updateInfo(Request $request)
    {
        $user = auth()->user();
        $profil = $user->profil();

        // Validation selon le type d'utilisateur
        $rules = [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
        ];

        // Règles spécifiques aux étudiants
        if ($user->type_utilisateur === 'etudiant') {
            $rules['date_naissance'] = 'required|date';
            $rules['lieu_naissance'] = 'required|string|max:255';
        }

        // Règles spécifiques aux enseignants
        if ($user->type_utilisateur === 'enseignant') {
            $rules['specialite'] = 'nullable|string|max:255';
            $rules['departement'] = 'nullable|string|max:255';
        }

        $validated = $request->validate($rules, [
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'nom.required' => 'Le nom est obligatoire',
            'prenom.required' => 'Le prénom est obligatoire',
        ]);

        // Mise à jour de l'email
        $user->update([
            'email' => $validated['email'],
        ]);

        // Mise à jour du profil
        $profil->update([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'date_naissance' => $validated['date_naissance'] ?? $profil->date_naissance,
            'lieu_naissance' => $validated['lieu_naissance'] ?? $profil->lieu_naissance,
            'specialite' => $validated['specialite'] ?? $profil->specialite,
            'departement' => $validated['departement'] ?? $profil->departement,
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Changer le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire',
            'password.required' => 'Le nouveau mot de passe est obligatoire',
            'password.confirmed' => 'Les mots de passe ne correspondent pas',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        ]);

        $user = auth()->user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect'
            ]);
        }

        // Mettre à jour le mot de passe
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Mot de passe modifié avec succès !');
    }

    /**
     * Uploader une photo de profil
     */
    
    public function updateAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'avatar.required' => 'Veuillez sélectionner une image',
            'avatar.image' => 'Le fichier doit être une image',
            'avatar.mimes' => 'L\'image doit être au format JPEG, PNG ou JPG',
            'avatar.max' => 'L\'image ne doit pas dépasser 2 Mo',
        ]);

        $user = auth()->user();

        // Supprimer l'ancien avatar s'il existe
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        // Enregistrer le nouvel avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        
        $user->update([
            'avatar' => $path
        ]);

        return redirect()->route('profil.index')
            ->with('success', 'Photo de profil mise à jour avec succès !');
    }
    
}
