<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    
    protected $fillable = [
        'user_id',
        'matricule',
        'nom',
        'prenom',
        'date_naissance',
        'lieu_naissance',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function resultats()
    {
        return $this->hasMany(Resultat::class);
    }

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'inscriptions')
                    ->withPivot('niveau_id', 'annee_academique', 'statut')
                    ->withTimestamps();
    }

    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getAgeAttribute()
    {
        return $this->date_naissance->age;
    }
}
