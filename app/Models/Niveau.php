<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Niveau extends Model
{
    protected $fillable = [
        'filiere_id',
        'code',
        'nom',
        'ordre',
        'credits_requis',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    public function resultats(): HasMany
    {
        return $this->hasMany(Resultat::class);
    }

    // ⬇️ NOUVELLE RELATION
    public function matieres(): BelongsToMany
    {
        return $this->belongsToMany(Matiere::class, 'filiere_matiere_niveau', 'niveau_id', 'matiere_id')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }
}