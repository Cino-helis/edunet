<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FiliereController;
use App\Http\Controllers\Admin\EtudiantController;
use App\Http\Controllers\Admin\MatiereController;
use App\Http\Controllers\Admin\NiveauController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\Admin\StatistiqueController;
use App\Http\Controllers\Admin\ParametreController;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Administrateur
    Route::middleware('role:administrateur')->prefix('admin')->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // CRUD Filières
        Route::resource('filieres', FiliereController::class);
    
        // CRUD Étudiants
        Route::resource('etudiants', EtudiantController::class);
    
        // CRUD Matières
        Route::resource('matieres', MatiereController::class);

        // CRUD Notes
        Route::resource('notes', NoteController::class);

        // Saisie groupée
        Route::get('/notes-saisie-groupee', [NoteController::class, 'saisieGroupee'])
            ->name('notes.saisie-groupee');
        Route::post('/notes-saisie-groupee', [NoteController::class, 'storeSaisieGroupee'])
            ->name('notes.store-groupee');


        // Moyennes
        Route::get('/notes/moyennes/{etudiant}', [NoteController::class, 'calculerMoyennes'])
             ->name('notes.moyennes');

        // AJAX - Étudiants par niveau
        Route::get('/api/etudiants-by-niveau', [NoteController::class, 'getEtudiantsByNiveau'])
             ->name('api.etudiants-by-niveau');

        // Statistiques
        Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques');
        Route::get('/statistiques/export', [StatistiqueController::class, 'export'])->name('statistiques.export');
    
        // Paramètres
        Route::get('/parametres', [ParametreController::class, 'index'])->name('parametres');
        Route::post('/parametres/clear-cache', [ParametreController::class, 'clearCache'])->name('parametres.clear-cache');
        Route::post('/parametres/optimize', [ParametreController::class, 'optimize'])->name('parametres.optimize');
        Route::post('/parametres/backup', [ParametreController::class, 'backup'])->name('parametres.backup');
    }); //  Fermeture du groupe admin

    // Dashboard Enseignant
    Route::middleware('role:enseignant')->prefix('enseignant')->name('enseignant.')->group(function () {
        Route::get('/dashboard', function () {
            return view('enseignant.dashboard');
        })->name('dashboard');
    });

    // Dashboard Étudiant
    Route::middleware('role:etudiant')->prefix('etudiant')->name('etudiant.')->group(function () {
        Route::get('/dashboard', function () {
            return view('etudiant.dashboard');
        })->name('dashboard');
    });

    // Profil utilisateur (accessible à tous les rôles)
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/informations', [ProfilController::class, 'updateInfo'])->name('profil.update-info');
    Route::put('/profil/mot-de-passe', [ProfilController::class, 'updatePassword'])->name('profil.update-password');
    Route::post('/profil/avatar', [ProfilController::class, 'updateAvatar'])->name('profil.update-avatar');
    
});

// Routes temporaires pour les liens (à développer)
Route::get('/login/otp', function () {
    return 'Page OTP en construction';
})->name('login.otp');

Route::get('/password/reset', function () {
    return 'Page de réinitialisation en construction';
})->name('password.request');

Route::get('/register', function () {
    return 'Page d\'inscription en construction';
})->name('register');