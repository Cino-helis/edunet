<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Administrateur;
use App\Models\Enseignant;
use App\Models\Etudiant;
use App\Models\Filiere;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Inscription;
use Illuminate\Support\Facades\Hash;
use App\Models\Resultat;
use App\Models\EmploiTemps;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Créer un Administrateur
        $adminUser = User::create([
            'email' => 'admin@edunet.com',
            'password' => Hash::make('password123'),
            'type_utilisateur' => 'administrateur',
            'email_verified_at' => now(),
        ]);

        Administrateur::create([
            'user_id' => $adminUser->id,
            'nom' => 'KOUKPAKI',
            'prenom' => 'Enoch',
            'privileges' => ['full_access'],
        ]);

        // 2. Créer un Enseignant
        $enseignantUser = User::create([
            'email' => 'enseignant@edunet.com',
            'password' => Hash::make('password123'),
            'type_utilisateur' => 'enseignant',
            'email_verified_at' => now(),
        ]);

        Enseignant::create([
            'user_id' => $enseignantUser->id,
            'nom' => 'HELIS',
            'prenom' => 'Cino',
            'specialite' => 'Mathématiques',
            'departement' => 'Sciences',
        ]);

        // 3. Créer un Étudiant
        $etudiantUser = User::create([
            'email' => 'etudiant@edunet.com',
            'password' => Hash::make('password123'),
            'type_utilisateur' => 'etudiant',
            'email_verified_at' => now(),
        ]);

        Etudiant::create([
            'user_id' => $etudiantUser->id,
            'matricule' => 'ETU2024001',
            'nom' => 'KOUKPAKI',
            'prenom' => 'Ketsia',
            'date_naissance' => '2002-05-15',
            'lieu_naissance' => 'Lome',
        ]);

        $this->command->info('✅ Tous les comptes de test créés !');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Administrateur', 'admin@edunet.com', 'password123'],
                ['Enseignant', 'enseignant@edunet.com', 'password123'],
                ['Étudiant', 'etudiant@edunet.com', 'password123'],
            ]
        );
        // 2. Créer des filières
        $informatique = Filiere::create([
            'code' => 'INFO',
            'nom' => 'Informatique',
            'description' => 'Licence en Informatique',
            'duree_annees' => 3,
        ]);

        $maths = Filiere::create([
            'code' => 'MATH',
            'nom' => 'Mathématiques',
            'description' => 'Licence en Mathématiques',
            'duree_annees' => 3,
        ]);

        // 3. Créer des niveaux
        $l1Info = Niveau::create([
            'filiere_id' => $informatique->id,
            'code' => 'L1',
            'nom' => 'Licence 1',
            'ordre' => 1,
            'credits_requis' => 60,
        ]);

        // 4. Créer des matières
        $matiere1 = Matiere::create([
            'code' => 'PROG101',
            'nom' => 'Programmation',
            'credits' => 6,
            'coefficient' => 2,
            'semestre' => 1,
            'type' => 'obligatoire',
        ]);

        // 5. Créer quelques notes pour avoir des statistiques
        $this->command->info('✅ Données de test créées avec succès !');
    }
}