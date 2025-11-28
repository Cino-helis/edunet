<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedNotification extends Notification
{
    use Queueable;

    protected $password;
    protected $role;

    public function __construct($password, $role)
    {
        $this->password = $password;
        $this->role = $role;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bienvenue sur EduNet - Vos identifiants de connexion')
            ->greeting('Bonjour ' . $notifiable->profil()->nom_complet . ',')
            ->line('Votre compte ' . ucfirst($this->role) . ' a été créé avec succès sur EduNet.')
            ->line('**Vos identifiants de connexion :**')
            ->line('Email : ' . $notifiable->email)
            ->line('Mot de passe temporaire : ' . $this->password)
            ->line('**⚠️ IMPORTANT : Veuillez changer votre mot de passe dès votre première connexion.**')
            ->action('Se connecter', url('/login'))
            ->line('Pour des raisons de sécurité, ne partagez jamais vos identifiants.')
            ->salutation('Cordialement, L\'équipe EduNet');
    }
}