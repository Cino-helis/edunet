<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enseignant extends Model
{

    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'specialite',
        'departement',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'affectations')
                    ->withPivot('niveau_id', 'annee_academique')
                    ->withTimestamps();
    }

    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }
}
