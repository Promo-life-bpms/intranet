<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthorizeRequest extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $receptor;
    public $emisor;
    public $type_request;
    public $details;
    public function __construct($receptor, $emisor, $type_request, $details)
    {
        $this->receptor = $receptor;
        $this->emisor = $emisor;
        $this->type_request = $type_request;
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('mail.permissionrequest.AuthorizeRequest', [
                'url' => url('/'),
                'receptor' => $this->receptor,
                'emisor' => $this->emisor,
                'type_request' => $this->type_request,
                'details' => $this->details,
            ])
            ->subject('Solicitud de vacaciones o permiso aprobado')
            ->from('adminportales@promolife.com.mx', 'Intranet Corporativa BH - PL');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
