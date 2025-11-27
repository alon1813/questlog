<?php
// app/Notifications/VerifyEmailNotification.php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject('✅ Verifica tu Correo Electrónico - QuestLog')
            ->greeting('¡Bienvenido a QuestLog!')
            ->line('Gracias por registrarte en QuestLog. Estás a un paso de comenzar tu aventura.')
            ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
            ->action('Verificar Correo Electrónico', $url)
            ->line('Una vez verificado, recibirás tu email de bienvenida oficial y podrás explorar todas las funciones de QuestLog.')
            ->line('Si no creaste una cuenta, no es necesario realizar ninguna acción.')
            ->line('Este enlace expirará en 60 minutos.')
            ->salutation('¡Nos vemos en el juego! 🎮 El equipo de QuestLog');
    }
}