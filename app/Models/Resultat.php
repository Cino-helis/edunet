<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    
    protected $fillable = [
        'etudiant_id',
        'niveau_id',
        'annee_academique',
        'semestre',
        'moyenne_generale',
        'mention',
        'statut_validation',
    ];

    protected $casts = [
        'moyenne_generale' => 'float',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    // Calculer automatiquement la mention
    public function calculerMention()
    {
        if ($this->moyenne_generale >= 16) return 'Excellent';
        if ($this->moyenne_generale >= 14) return 'Très Bien';
        if ($this->moyenne_generale >= 12) return 'Bien';
        if ($this->moyenne_generale >= 10) return 'Assez Bien';
        if ($this->moyenne_generale >= 8) return 'Passable';
        return null;
    }
}
