<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    
    protected $fillable = [
        'code',
        'nom',
        'description',
        'duree_annees',
    ];

    public function niveaux()
    {
        return $this->hasMany(Niveau::class)->orderBy('ordre');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'inscriptions')
                    ->withPivot('niveau_id', 'annee_academique', 'statut')
                    ->withTimestamps();
    }
}
