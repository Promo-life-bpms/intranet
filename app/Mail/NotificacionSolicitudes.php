<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SplSubject;

class NotificacionSolicitudes extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = "Solicitud de Servicios de Sistemas y Comunicaciones";

    // public $usuario;

    public function __construct($usuario)
    {
        // $this->usuario = $usuario;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.notifySolicitud');
    }
}
