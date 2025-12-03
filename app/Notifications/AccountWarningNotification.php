<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountWarningNotification extends Notification
{
    use Queueable;

    public function __construct(public int $warningCount)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $remainingWarnings = 3 - $this->warningCount;
        
        return (new MailMessage)
            ->subject('⚠️ Advertencia #' . $this->warningCount . ' en tu cuenta - QuestLog')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Has recibido una advertencia en tu cuenta de QuestLog.')
            ->line("**Advertencias acumuladas: {$this->warningCount} de 3**")
            ->line($remainingWarnings > 0 
                ? "Te quedan {$remainingWarnings} advertencia(s) antes de que tu cuenta sea suspendida."
                : "⚠️ Has alcanzado el límite de advertencias. Tu cuenta será suspendida.")
            ->line('Por favor, revisa nuestras normas de la comunidad para evitar futuras infracciones.')
            ->action('Ver Normas', url('/normas'))
            ->line('Gracias por tu comprensión.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "Has recibido tu advertencia #{$this->warningCount}. Al tercer aviso tu cuenta será suspendida.",
            'type' => 'account_warning',
            'warning_count' => $this->warningCount,
        ];
    }
}