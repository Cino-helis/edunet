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

    public function enseignant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function filiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo  // ⬅️ NOUVELLE RELATION
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function matiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }
}