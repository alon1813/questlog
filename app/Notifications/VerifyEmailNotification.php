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
            ->line('Por favor, haz clic en el botón de abajo para verificar tu dirección de correo electrónico.')
            ->action('Verificar Correo Electrónico', $url)
            ->line('Si no creaste una cuenta, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, El equipo de QuestLog');
    }
}