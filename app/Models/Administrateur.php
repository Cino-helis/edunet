<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'privileges',
    ];

    protected $casts = [
        'privileges' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getNomCompletAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }
}
