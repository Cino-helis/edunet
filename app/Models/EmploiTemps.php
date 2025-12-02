<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiTemps extends Model
{
    protected $fillable = [
        'niveau_id',
        'matiere_id',
        'enseignant_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'type',
        'salle',
        'annee_academique',
        'description',
        'couleur',
        'est_actif',
    ];

    protected $casts = [
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime',
        'est_actif' => 'boolean',
    ];

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }
}