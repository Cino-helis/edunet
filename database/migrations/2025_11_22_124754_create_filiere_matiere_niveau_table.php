<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('filiere_matiere_niveau', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiere_id')->constrained()->onDelete('cascade');
            $table->foreignId('niveau_id')->constrained()->onDelete('cascade');
            $table->foreignId('matiere_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Une matière ne peut être affectée qu'une fois à un niveau d'une filière
            $table->unique(['filiere_id', 'niveau_id', 'matiere_id'], 'filieres_matieres_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filiere_matiere_niveau');
    }
};