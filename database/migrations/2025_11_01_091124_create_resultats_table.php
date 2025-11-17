<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resultats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->string('annee_academique'); // 2024-2025
            $table->integer('semestre'); // 1 ou 2
            $table->decimal('moyenne_generale', 5, 2)->nullable();
            $table->enum('mention', ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'Excellent'])->nullable();
            $table->enum('statut_validation', ['en_attente', 'valide', 'ajourne', 'redouble'])->default('en_attente');
            $table->timestamps();

            // Un étudiant a un seul résultat par niveau, semestre et année
            $table->unique(
    ['etudiant_id', 'niveau_id', 'annee_academique', 'semestre'],
    'resultats_unique_idx' // Nom court
);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultats');
    }
};
