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
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade'); // ⬅️ AJOUT
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->string('annee_academique');
            $table->date('date_affectation');
            $table->timestamps();

            // Un enseignant ne peut affecter une matière qu'une fois par niveau par année
            $table->unique(
                ['enseignant_id', 'filiere_id', 'niveau_id', 'matiere_id', 'annee_academique'],
                'affectations_unique_idx' // Nom court
);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
