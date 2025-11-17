<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'type_utilisateur',
        'otp_secret',
        'derniere_connexion',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'derniere_connexion' => 'datetime',
        'password' => 'hashed',
    ];

    // Relations polymorphiques
    public function administrateur()
    {
        return $this->hasOne(Administrateur::class);
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }

    public function etudiant()
    {
        return $this->hasOne(Etudiant::class);
    }

    // Méthode pour obtenir le profil selon le type
    public function profil()
    {
        return match($this->type_utilisateur) {
            'administrateur' => $this->administrateur,
            'enseignant' => $this->enseignant,
            'etudiant' => $this->etudiant,
            default => null,
        };
    }
}
