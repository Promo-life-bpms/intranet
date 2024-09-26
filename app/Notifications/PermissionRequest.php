<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermissionRequest extends Notification
{
    use Queueable;

    public $receptor;
    public $emisor;
    public $type_request;
    public $days;
    public $time;
    public $reveal;
    public $details;
    public $moreInformation;
    public function __construct($receptor, $emisor, $type_request, $days, $time, $reveal, $details, $moreInformation)
    {
        $this->receptor = $receptor;
        $this->emisor = $emisor;
        $this->type_request = $type_request;
        $this->days = $days;
        $this->time = $time;
        $this->reveal = $reveal;
        $this->details = $details;
        $this->moreInformation = $moreInformation;
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


    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('mail.permissionrequest.CreateRequest', [
                'receptor' => $this->receptor,
                'emisor' => $this->emisor,
                'type_request' => $this->type_request,
                'days' => $this->days,
                'time' => $this->time,
                'reveal' => $this->reveal,
                'details' => $this->details,
                'moreInformation' => $this->moreInformation
            ])
            ->subject('TE HAN INVITADO A UNA REUNIÓN')
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
