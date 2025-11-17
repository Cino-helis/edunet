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

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function enseignants()
    {
        return $this->belongsToMany(Enseignant::class, 'affectations')
                    ->withPivot('niveau_id', 'annee_academique')
                    ->withTimestamps();
    }
}
