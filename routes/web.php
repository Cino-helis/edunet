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
use App\Http\Controllers\Admin\EnseignantController;
use App\Http\Controllers\Admin\AffectationController;
use App\Http\Controllers\Admin\InscriptionController;

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

        // CRUD Enseignants ⬅️ AJOUTEZ CETTE LIGNE
        Route::resource('enseignants', EnseignantController::class);
    
        // CRUD Matières
        Route::resource('matieres', MatiereController::class);

        // CRUD Notes
        Route::resource('notes', NoteController::class);

        // CRUD Enseignants
        Route::resource('enseignants', EnseignantController::class);
    
        // CRUD Niveaux
        Route::resource('niveaux', NiveauController::class);

        // CRUD Inscriptions
        Route::resource('inscriptions', InscriptionController::class);

        // CRUD Affectations (avec filiere_id maintenant)
         Route::resource('affectations', \App\Http\Controllers\Admin\AffectationController::class)
            ->except(['show', 'edit', 'update']);

        // Gérer les matières d'un niveau
        Route::get('/niveaux/{niveau}/matieres', [\App\Http\Controllers\Admin\NiveauMatiereController::class, 'edit'])
            ->name('niveaux.matieres');
        Route::put('/niveaux/{niveau}/matieres', [\App\Http\Controllers\Admin\NiveauMatiereController::class, 'update'])
            ->name('niveaux.matieres.update');


        // Saisie groupée
        Route::get('/notes-saisie-groupee', [NoteController::class, 'saisieGroupee'])
            ->name('notes.saisie-groupee');
        Route::post('/notes-saisie-groupee', [NoteController::class, 'storeSaisieGroupee'])
            ->name('notes.store-groupee');


        // Moyennes
        Route::get('/notes/moyennes/{etudiant}', [NoteController::class, 'calculerMoyennes'])
             ->name('notes.moyennes');

        // API - Niveaux par filière (pour affectations et inscriptions)
        Route::get('/api/niveaux-by-filiere', [\App\Http\Controllers\Admin\InscriptionController::class, 'getNiveauxByFiliere'
            ])->name('api.niveaux-by-filiere');

        // API - Matières par niveau (pour affectations)
        Route::get('/api/matieres-by-niveau', [\App\Http\Controllers\Admin\AffectationController::class, 'getMatieresByNiveau'
            ])->name('api.matieres-by-niveau');

        // API - Étudiants par niveau (pour notes)
        Route::get('/api/etudiants-by-niveau', [NoteController::class, 'getEtudiantsByNiveau'
            ])->name('api.etudiants-by-niveau');

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
    Route::get('/dashboard', [\App\Http\Controllers\Enseignant\DashboardController::class, 'index'])->name('dashboard');
    
     Route::get('/classes', [\App\Http\Controllers\Enseignant\ClasseController::class, 'index'])->name('classes');

    // Gestion des notes
    Route::resource('notes', \App\Http\Controllers\Enseignant\NoteController::class);
    
    // Saisie groupée
    Route::get('/notes-saisie-groupee', [\App\Http\Controllers\Enseignant\NoteController::class, 'saisieGroupee'])
        ->name('notes.saisie-groupee');
    Route::post('/notes-saisie-groupee', [\App\Http\Controllers\Enseignant\NoteController::class, 'storeSaisieGroupee'])
        ->name('notes.store-groupee');
    
    // AJAX - Étudiants par niveau
    Route::get('/api/etudiants-by-niveau', [\App\Http\Controllers\Enseignant\NoteController::class, 'getEtudiantsByNiveau'])
        ->name('api.etudiants-by-niveau');
});

    // Dashboard Étudiant
    Route::middleware('role:etudiant')->prefix('etudiant')->name('etudiant.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Etudiant\DashboardController::class, 'index'])
            ->name('dashboard');

    // Bulletin (Relevé de notes)
    Route::get('/bulletin', [\App\Http\Controllers\Etudiant\BulletinController::class, 'index'])
        ->name('bulletin.index');
    Route::get('/bulletin/download-pdf', [\App\Http\Controllers\Etudiant\BulletinController::class, 'downloadPDF'])
        ->name('bulletin.download-pdf');
    
    // Mes matières
    Route::get('/matieres', [\App\Http\Controllers\Etudiant\MatiereController::class, 'index'])
        ->name('matieres.index');
    Route::get('/matieres/{matiere}', [\App\Http\Controllers\Etudiant\MatiereController::class, 'show'])
        ->name('matieres.show');
    
    // Notes
    Route::get('/notes', [\App\Http\Controllers\Etudiant\NoteController::class, 'index'])
        ->name('notes.index');
    Route::get('/notes/{note}', [\App\Http\Controllers\Etudiant\NoteController::class, 'show'])
        ->name('notes.show');
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
