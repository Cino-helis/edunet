<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    
    protected $fillable = [
        'code',
        'nom',
        'description',
        'duree_annees',
    ];

    public function niveaux(): HasMany
    {
        return $this->hasMany(Niveau::class)->orderBy('ordre');
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    /**
     * Les affectations de cette filière
     */
    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    public function etudiants(): BelongsToMany
    {
        return $this->belongsToMany(Etudiant::class, 'inscriptions')
                    ->withPivot('niveau_id', 'annee_academique', 'statut')
                    ->withTimestamps();
    }
}
