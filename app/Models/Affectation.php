<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    
    protected $fillable = [
        'enseignant_id',
        'matiere_id',
        'niveau_id',
        'annee_academique',
        'date_affectation',
    ];

    protected $casts = [
        'date_affectation' => 'date',
    ];

    public function enseignant()
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
