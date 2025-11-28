<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('password_change_required')->default(true)->after('password');
    });
}

// Modifier LoginController@login
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
        'type_utilisateur' => ['required', 'in:etudiant,enseignant,administrateur'],
    ]);

    if (Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
        'type_utilisateur' => $request->type_utilisateur
    ], $request->filled('remember'))) {

        $request->session()->regenerate();
        $user = Auth::user();
        $user->update(['derniere_connexion' => now()]);

        // Vérifier si changement de mot de passe requis
        if ($user->password_change_required) {
            return redirect()->route('password.change.required')
                ->with('warning', 'Pour des raisons de sécurité, veuillez changer votre mot de passe temporaire.');
        }

        return match($user->type_utilisateur) {
            'administrateur' => redirect()->route('admin.dashboard'),
            'enseignant' => redirect()->route('enseignant.dashboard'),
            'etudiant' => redirect()->route('etudiant.dashboard'),
            default => redirect()->route('welcome'),
        };
    }

    return back()->withErrors([
        'email' => 'Identifiants ou rôle incorrects.',
    ])->withInput($request->only('email', 'type_utilisateur'));
}
};