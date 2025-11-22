<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $fillable = [
        'enseignant_id',
        'filiere_id',      // ⬅️ AJOUT
        'niveau_id',
        'matiere_id',
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

    public function filiere()  // ⬅️ NOUVELLE RELATION
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}