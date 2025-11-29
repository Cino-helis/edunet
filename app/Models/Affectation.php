<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }

    public function filiere(): BelongsTo  // ⬅️ NOUVELLE RELATION
    {
        return $this->belongsTo(Filiere::class);
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class);
    }

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }
}