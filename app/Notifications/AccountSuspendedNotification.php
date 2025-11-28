<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSuspendedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('⚠️ Importante: Tu cuenta ha sido suspendida - QuestLog') 
                    ->greeting('Hola ' . $notifiable->name . ',')
                    ->line('Te informamos que tu cuenta en QuestLog ha sido suspendida debido al incumplimiento de nuestras normas.')
                    ->line('Durante este periodo, no podrás acceder a tu cuenta ni realizar ninguna acción en la plataforma.')
                    ->action('Contactar con Administradores', 'mailto:admin@questlog.com') 
                    ->line('Si crees que se trata de un error o deseas recuperar tu cuenta, por favor ponte en contacto con nosotros.')
                    ->salutation('El equipo de QuestLog');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Tu cuenta ha sido suspendida. Contacta con soporte para más información.',
            'type' => 'account_suspended',
        ];
    }
}
