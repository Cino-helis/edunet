<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmploiTemps;
use App\Models\Niveau;
use App\Models\Matiere;
use App\Models\Enseignant;
use App\Models\Classe;
use Carbon\Carbon;

class EmploiTempsSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer des données existantes
        $niveau = Niveau::first();
        $matieres = Matiere::take(5)->get();
        $enseignants = Enseignant::take(3)->get();
        
        if (!$niveau || $matieres->isEmpty() || $enseignants->isEmpty()) {
            $this->command->warn('Veuillez d\'abord créer des niveaux, matières et enseignants');
            return;
        }
        
        // Exemples de cours pour la semaine
        $horaires = [
            ['debut' => '08:30', 'fin' => '10:00'],
            ['debut' => '10:00', 'fin' => '11:30'],
            ['debut' => '13:00', 'fin' => '14:30'],
            ['debut' => '14:30', 'fin' => '16:00'],
        ];
        
        $jours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
        $types = ['Cours', 'TD', 'TP'];
        
        foreach ($jours as $indexJour => $jour) {
            foreach ($horaires as $indexHoraire => $horaire) {
                // Ne pas remplir tous les créneaux
                if (rand(0, 1) == 0) continue;
                
                $matiere = $matieres->random();
                $enseignant = $enseignants->random();
                
                EmploiTemps::create([
                    'niveau_id' => $niveau->id,
                    'matiere_id' => $matiere->id,
                    'enseignant_id' => $enseignant->id,
                    'jour' => $jour,
                    'heure_debut' => $horaire['debut'],
                    'heure_fin' => $horaire['fin'],
                    'type' => $types[array_rand($types)],
                    'salle' => 'A' . rand(101, 305),
                    'annee_academique' => '2024-2025',
                ]);
            }
        }
        
        $this->command->info('Emploi du temps créé avec succès !');
    }
}