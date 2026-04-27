<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AfiliacionAprobadaNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected User $user,
        protected string $resetUrl,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ATSA Tucuman - solicitud de afiliacion aprobada')
            ->greeting('Hola '.$this->user->name)
            ->line('Tu solicitud de afiliacion fue aprobada por ATSA Tucuman.')
            ->line('Tu numero de afiliado es: '.$this->user->numero_afiliado)
            ->line('Ya podes activar tu acceso al portal de afiliados desde el siguiente enlace.')
            ->action('Crear mi contraseña', $this->resetUrl)
            ->line('Si no solicitaste este acceso, por favor comunicate con ATSA Tucuman.');
    }
}
