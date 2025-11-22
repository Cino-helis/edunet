<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

    public function resultats()
    {
        return $this->hasMany(Resultat::class);
    }

    // ⬇️ NOUVELLE RELATION
    public function matieres()
    {
        return $this->belongsToMany(Matiere::class, 'filiere_matiere_niveau')
                    ->withPivot('filiere_id')
                    ->withTimestamps();
    }
}