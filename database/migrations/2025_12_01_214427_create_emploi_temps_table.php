<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emploi_temps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->enum('jour', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->enum('type', ['Cours', 'TD', 'TP', 'Examen', 'Projet', 'Conférence', 'Soutenance'])->default('Cours');
            $table->string('salle', 50);
            $table->string('annee_academique', 10);
            $table->text('description')->nullable();
            $table->string('couleur', 7)->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
            
            $table->index(['niveau_id', 'jour', 'annee_academique']);
            $table->index(['enseignant_id', 'jour', 'heure_debut']);
            $table->index('annee_academique');
            
            $table->unique(
                ['niveau_id', 'jour', 'heure_debut', 'annee_academique'],
                'unique_horaire_niveau'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emploi_temps');
    }
};