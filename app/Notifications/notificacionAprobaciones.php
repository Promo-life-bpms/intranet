<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionAprobaciones extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

     public $TI;
     public $name;

    public function __construct($TI, $name)
    {
        $this->TI=$TI;
        $this->name=$name;
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
            ->markdown('mail.apro.Aprobacion',[
                'TI'=>$this->TI,
                'name'=>$this->name
            ])
            ->subject(' Aprobación de RH de Solicitudes de Servicios de Sistemas y Comunicaciones')
            ->from('admin@intranet.promolife.lat', 'Intranet Corporativa BH - PL');
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
