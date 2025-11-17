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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('enseignant_id')->constrained()->onDelete('cascade');
            $table->decimal('valeur', 5, 2); // Note sur 20
            $table->enum('type_evaluation', ['CC', 'TP', 'Examen', 'Projet'])->default('Examen');
            $table->string('annee_academique'); // 2024-2025
            $table->timestamp('date_saisie')->useCurrent();
            $table->timestamps();

             // Donner un nom court à l'index unique
            $table->unique(
                ['etudiant_id', 'matiere_id', 'type_evaluation', 'annee_academique'],
                'notes_unique_idx' // ✅ Nom court personnalisé
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
