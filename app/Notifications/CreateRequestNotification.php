<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $type;
    public $emisor_name;
    public $receptor_name;
    public function __construct($type, $emisor_name, $receptor_name)
    {
        $this->type = $type;
        $this->emisor_name = $emisor_name;
        $this->receptor_name = $receptor_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->markdown('mail.request.create', [
                'url' => url('/'),  'type' => $this->type,
                'emisor_name' => $this->emisor_name,
                'receptor_name' => $this->receptor_name,
            ])
            ->subject('Solicitud de ausencia')
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
            'type' => $this->type,
            'emisor_name' => $this->emisor_name,
        ];
    }
}
