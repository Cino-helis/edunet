<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    
    protected $fillable = [
        'etudiant_id',
        'filiere_id',
        'niveau_id',
        'annee_academique',
        'statut',
        'date_inscription',
    ];

    protected $casts = [
        'date_inscription' => 'date',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau()
    {
        return $this->belongsTo(Niveau::class);
    }
}
