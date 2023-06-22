<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionPJ extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $PM;
    public $dueño;
    public $title;
    public $nombre_sala;
    public $ubicacion;
    public $hora_inicio;
    public $hora_fin;
    public $engrave;
    public $invitados;
    public $cantidadSillas;
    public $cantidadProjectores;
    public $description;
    public function __construct($PM, $dueño, $title, $nombre_sala, $ubicacion, $hora_inicio, $hora_fin, $engrave,
                                $invitados, $cantidadSillas, $cantidadProjectores, $description)
    {
        $this->PM=$PM;
        $this->dueño=$dueño;
        $this->title=$title;
        $this->nombre_sala=$nombre_sala;
        $this->ubicacion=$ubicacion;
        $this->hora_inicio=$hora_inicio;
        $this->hora_fin=$hora_fin;
        $this->engrave=$engrave;
        $this->invitados=$invitados;
        $this->cantidadSillas=$cantidadSillas;
        $this->cantidadProjectores=$cantidadProjectores;
        $this->description=$description;
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
                    ->markdown('mail.reservation.AvisoProjectManager',[
                        'PM'=>$this->PM,
                        'dueño'=>$this->dueño,
                        'title'=>$this->title,
                        'nombre_sala'=>$this->nombre_sala,
                        'ubicacion'=>$this->ubicacion,
                        'hora_inicio'=>$this->hora_inicio,
                        'hora_fin'=>$this->hora_fin,
                        'engrave'=>$this->engrave,
                        'invitados'=>$this->invitados,
                        'cantidadSillas'=>$this->cantidadSillas,
                        'cantidadProjectores'=>$this->cantidadProjectores,
                        'description'=>$this->description,
                    ])
                    ->subject('HAN CREADO UNA RESERVACIÓN')
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
