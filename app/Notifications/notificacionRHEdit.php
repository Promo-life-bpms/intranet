<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionRHEdit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $RH;
    public $dueño;
    public $nombre_sala;
    public $ubicacion;
    public $diainicio;
    public $mesinicio;
    public $horainicio;
    public $diafin;
    public $mesfin;
    public $horafin;
    public $cantidadSillas;
    public $description;
    public function __construct($RH,$dueño,$nombre_sala, $ubicacion,$diainicio, $mesinicio, $horainicio,  
                                $diafin, $mesfin, $horafin,$cantidadSillas,$description)
    {
        $this->RH=$RH;
        $this->dueño=$dueño;
        $this->nombre_sala=$nombre_sala;
        $this->ubicacion=$ubicacion;
        $this->diainicio=$diainicio;
        $this->mesinicio=$mesinicio;
        $this->horainicio=$horainicio;
        $this->diafin=$diafin;
        $this->mesfin=$mesfin;
        $this->horafin=$horafin;
        $this->cantidadSillas=$cantidadSillas;
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
                    ->markdown('mail.reservation.MaterialRHEdit',[
                        'RH'=>$this->RH,
                        'dueño'=>$this->dueño,
                        'nombre_sala'=>$this->nombre_sala,
                        'ubicacion'=>$this->ubicacion,
                        'diainicio'=>$this->diainicio,
                        'mesinicio'=>$this->mesinicio,
                        'horainicio'=>$this->horainicio,
                        'diafin'=>$this->diafin,
                        'mesfin'=>$this->mesfin,
                        'horafin'=>$this->horafin,
                        'cantidadSillas'=>$this->cantidadSillas,
                        'description'=>$this->description,
                    ])
                    ->subject('HAN EDITADO LA SOLICITUD DE MATERIAL (SILLAS)')
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
