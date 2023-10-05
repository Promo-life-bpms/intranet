<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificacionEdit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $emisor_name;
    public $emisor_lastname;
    public $receptor_name;
    public $diainicio;
    public $mesinicio;
    public $horainicio;
    public $diafin;
    public $mesfin;
    public $horafin;
    public $locacion;
    public $nombre_sala;
    public $description;
    public function __construct($emisor_name, $emisor_lastname, $receptor_name, $diainicio, $mesinicio, $horainicio,  
                                $diafin, $mesfin, $horafin, $locacion, $nombre_sala, $description)
    {
        $this->emisor_name=$emisor_name;
        $this->emisor_lastname=$emisor_lastname;
        $this->receptor_name=$receptor_name;
        $this->diainicio=$diainicio;
        $this->mesinicio=$mesinicio;
        $this->horainicio=$horainicio;
        $this->diafin=$diafin;
        $this->mesfin=$mesfin;
        $this->horafin=$horafin;
        $this->locacion=$locacion;
        $this->nombre_sala=$nombre_sala;
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
                    ->markdown('mail.reservation.InvitacionEdit',[
                        'emisor_name'=>$this->emisor_name,
                        'emisor_lastname'=>$this->emisor_lastname,
                        'diainicio'=>$this->diainicio,
                        'mesinicio'=>$this->mesinicio,
                        'horainicio'=>$this->horainicio,
                        'diafin'=>$this->diafin,
                        'mesfin'=>$this->mesfin,
                        'horafin'=>$this->horafin,
                        'locacion'=>$this->locacion,
                        'nombre_sala'=>$this->nombre_sala,
                        'receptor_name'=>$this->receptor_name,
                        'description'=>$this->description,
                    ])
                    ->subject('EDITARON LA REUNIÓN, ECHALE UN VISTAZO')
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
