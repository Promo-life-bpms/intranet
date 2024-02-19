<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notificacionSistemasEdit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $Sistemas;
    public $dueño;
    public $nombre_sala;
    public $ubicacion;
    public $diainicio;
    public $mesinicio;
    public $horainicio;
    public $diafin;
    public $mesfin;
    public $horafin;
    public $cantidadProyectores;
    public $description;
    public function __construct($Sistemas, $dueño, $nombre_sala, $ubicacion, $diainicio, $mesinicio, $horainicio,  
                                $diafin, $mesfin, $horafin, $cantidadProyectores, $description)
    {
        $this->Sistemas=$Sistemas;
        $this->dueño=$dueño;
        $this->nombre_sala=$nombre_sala;
        $this->ubicacion=$ubicacion;
        $this->diainicio=$diainicio;
        $this->mesinicio=$mesinicio;
        $this->horainicio=$horainicio;
        $this->diafin=$diafin;
        $this->mesfin=$mesfin;
        $this->horafin=$horafin;
        $this->cantidadProyectores=$cantidadProyectores;
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
                    ->markdown('mail.reservation.MaterialSistemasEdit',[
                        'Sistemas'=>$this->Sistemas,
                        'dueño'=>$this->dueño,
                        'nombre_sala'=>$this->nombre_sala,
                        'ubicacion'=>$this->ubicacion,
                        'diainicio'=>$this->diainicio,
                        'mesinicio'=>$this->mesinicio,
                        'horainicio'=>$this->horainicio,
                        'diafin'=>$this->diafin,
                        'mesfin'=>$this->mesfin,
                        'horafin'=>$this->horafin,
                        'cantidadProyectores'=>$this->cantidadProyectores,
                        'description'=>$this->description,
                    ])
                    ->subject('HAN EDITADO LA SOLICITUD DE MATERIAL (PROYECTORES)')
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
