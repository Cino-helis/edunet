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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->string('annee_academique'); // 2024-2025
            $table->enum('statut', ['en_cours', 'validee', 'suspendue', 'abandonnee'])->default('en_cours','validee');
            $table->date('date_inscription');
            $table->timestamps();

            // Un étudiant ne peut être inscrit qu'une fois par niveau par année
            $table->unique(['etudiant_id', 'niveau_id', 'annee_academique']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
