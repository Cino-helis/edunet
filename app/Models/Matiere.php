<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matiere extends Model
{
    protected $fillable = [
        'code',
        'nom',
        'credits',
        'coefficient',
        'semestre',
        'type',
    ];

    protected $casts = [
        'coefficient' => 'float',
    ];

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }

    /**
     * Les niveaux auxquels cette matière est affectée
     */
    public function niveaux(): BelongsToMany
    {
        return $this->belongsToMany(Niveau::class, 'filiere_matiere_niveau', 'matiere_id', 'niveau_id')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }

    public function enseignants(): BelongsToMany
    {
        return $this->belongsToMany(Enseignant::class, 'affectations')
                    ->withPivot('niveau_id', 'annee_academique')
                    ->withTimestamps();
    }
}
