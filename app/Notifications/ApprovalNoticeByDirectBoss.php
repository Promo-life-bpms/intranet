<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalNoticeByDirectBoss extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $receptor;
    public $emisor;
    public $user;
    public $type_request;
    public $days;
    public $details;
    public function __construct($receptor, $emisor, $user, $type_request, $days, $details)
    {
        $this->receptor = $receptor;
        $this->emisor = $emisor;
        $this->user = $user;
        $this->type_request = $type_request;
        $this->days = $days;
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
            ->markdown('mail.permissionrequest.ApprovalNoticeByDirectBoss', [
                'url' => url('/'),
                'receptor' => $this->receptor,
                'emisor' => $this->emisor,
                'user' => $this->user,
                'type_request' => $this->type_request,
                'days' => $this->days,
                'details' => $this->details,
            ])
            ->subject('Solicitud de vacaciones o permiso pendiente')
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
