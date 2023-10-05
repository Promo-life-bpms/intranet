<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionPJEdit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $PM;
    public $dueño;
    public $dueño_lastname;
    public $title;
    public $nombre_sala;
    public $ubicacion;
    public $diainicio;
    public $mesinicio;
    public $horainicio;
    public $diafin;
    public $mesfin;
    public $horafin;
    public $engrave;
    public $invitados;
    public $cantidadSillas;
    public $cantidadProjectores;
    public $description;
    public function __construct($PM, $dueño, $dueño_lastname,$title, $nombre_sala, $ubicacion,$diainicio, $mesinicio, $horainicio,  
                                $diafin, $mesfin, $horafin, $engrave,$invitados, $cantidadSillas, $cantidadProjectores, 
                                $description)                           
    {
        $this->PM=$PM;
        $this->dueño=$dueño;
        $this->dueño_lastname=$dueño_lastname;
        $this->title=$title;
        $this->nombre_sala=$nombre_sala;
        $this->ubicacion=$ubicacion;
        $this->diainicio=$diainicio;
        $this->mesinicio=$mesinicio;
        $this->horainicio=$horainicio;
        $this->diafin=$diafin;
        $this->mesfin=$mesfin;
        $this->horafin=$horafin;
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
                    ->markdown('mail.reservation.AvisoProjectManagerEdit',[
                        'PM'=>$this->PM,
                        'dueño'=>$this->dueño,
                        'dueño_lastname'=>$this->dueño_lastname,
                        'title'=>$this->title,
                        'nombre_sala'=>$this->nombre_sala,
                        'ubicacion'=>$this->ubicacion,
                        'diainicio'=>$this->diainicio,
                        'mesinicio'=>$this->mesinicio,
                        'horainicio'=>$this->horainicio,
                        'diafin'=>$this->diafin,
                        'mesfin'=>$this->mesfin,
                        'horafin'=>$this->horafin,
                        'engrave'=>$this->engrave,
                        'invitados'=>$this->invitados,
                        'cantidadSillas'=>$this->cantidadSillas,
                        'cantidadProjectores'=>$this->cantidadProjectores,
                        'description'=>$this->description,
                    ])
                    ->subject('HAN MODIFICADO LA RESERVACIÓN')
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
