<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Afficher la page de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        // Validation
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'type_utilisateur' => ['required', 'in:etudiant,enseignant,administrateur'],
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'type_utilisateur.required' => 'Veuillez sélectionner votre rôle.',
            'type_utilisateur.in' => 'Le rôle sélectionné est invalide.',
        ]);

        // Vérifier que l'utilisateur existe et correspond au rôle
        $user = User::where('email', $request->email)
                    ->where('type_utilisateur', $request->type_utilisateur)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Aucun compte ne correspond à ces identifiants et ce rôle.',
            ])->withInput($request->only('email', 'type_utilisateur'));
        }

        // Tentative de connexion
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'type_utilisateur' => $request->type_utilisateur
        ], $request->filled('remember'))) {
            
            $request->session()->regenerate();

            // Mettre à jour la dernière connexion
            $user->update(['derniere_connexion' => now()]);

            // Rediriger selon le rôle
            return $this->redirectToDashboard($user->type_utilisateur);
        }

        return back()->withErrors([
            'password' => 'Le mot de passe est incorrect.',
        ])->withInput($request->only('email', 'type_utilisateur'));
    }

    /**
     * Redirection selon le rôle
     */
    protected function redirectToDashboard($role)
    {
        return match($role) {
            'administrateur' => redirect()->route('admin.dashboard'),
            'enseignant' => redirect()->route('enseignant.dashboard'),
            'etudiant' => redirect()->route('etudiant.dashboard'),
            default => redirect()->route('welcome'),
        };
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Vous êtes déconnecté avec succès.');
    }
}