<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    
    protected $fillable = [
        'etudiant_id',
        'matiere_id',
        'enseignant_id',
        'valeur',
        'type_evaluation',
        'annee_academique',
        'date_saisie',
    ];

    protected $casts = [
        'valeur' => 'float',
        'date_saisie' => 'datetime',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    // Vérifier si la note est une réussite
    public function estReussie()
    {
        return $this->valeur >= 10;
    }
}
