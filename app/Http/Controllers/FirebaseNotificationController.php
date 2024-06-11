<?php

namespace App\Http\Controllers;

use App\Models\Role;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FirebaseNotificationController extends Controller
{
    public function publication($user, $message)
    {
        /* $title ='Nueva Publicación';
        $body = '¡'. $user. ' ha realizado una nueva publicación!. ' . $message;
        $topic = "/topics/PUBLICACIONES";

        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */

    }

    public function communique($title_message,$message)
    {
        /* $title = $title_message;
        if($message == '.'){
            $body = '¡Se ha realizado un nuevo comunicado!. ' ;
        }else{
            $body = $message;
        }

        $topic = "/topics/COMUNICADOS";

        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
 */
    }

    public function createRequest($applicant_id)
    {
        //Notificacion del que crea la solicitud
        /* $title = 'Solicitud enviada';
        $body = '¡Tu solicitud ha sido enviada, le notificaremos a tu jefe directo para su aprobación!. ' ;
        $topic = '/topics'.'/'. strval($applicant_id) ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */

    }

    public function sendToManager($manager_id)
    {
         //Notificacion para el jefe directo
         /* $title_manager = 'Nueva solicitud';
         $body_manager = '¡Haz recibido una nueva solicitud de un colaborador!' ;
         $topic_manager = '/topics'.'/'. strval($manager_id) ;
         $client_manager = new Client(['verify' => false]);

         $body_manager = [
             'to' => $topic_manager,
                 'notification' => [
                     'title'=> $title_manager,
                     'body'=> $body_manager,
                 ],
         ];

         $response = $client_manager->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body_manager),
             ]
         ); */

    }

    public function sendToRh()
    {
        /* $usersRH =  Role::where('name', 'rh')->first()->users;
        foreach ($usersRH as $user) {

            //Notificacion del que crea la solicitud
            $title = 'Nueva solicitud';
            $body = '¡Tienes una nueva solicitud para aprobar de un colaborador.!' ;
            $topic = '/topics'.'/'. strval($user->id)   ;
            $client = new Client(['verify' => false]);

            $body = [
                'to' => $topic,
                    'notification' => [
                        'title'=> $title,
                        'body'=> $body,
                    ],
            ];

            $response = $client->request(
                'POST',
                'https://fcm.googleapis.com/fcm/send',
                    [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                    ],
                    'body' => json_encode($body),
                ]
            );

        } */

    }

    public function sendApprovedRequest($user_id)
    {
        //Notificacion del que crea la solicitud
        /* $title = '¡Solicitud Aprobada!';
        $body = 'Tu solicitud ha sido aprobada, visita la sección de solicitudes para ver mas detalles' ;
        $topic = '/topics'.'/'. strval($user_id)   ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
 */
    }


    public function sendRejectedRequest($user_id)
    {
        //Notificacion del que crea la solicitud
        /* $title = '¡Solicitud Rechazada!';
        $body = 'Tu solicitud ha sido rechazada, verifica la información e intenta nuevamente.' ;
        $topic = '/topics'.'/'. strval($user_id)   ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
 */
    }


    public function likePublication($user_id)
    {
        //Notificacion del que crea la solicitud
        /* $title = 'Publicación';
        $body = 'Haz recibido un nuevo me gusta en tu publicación.' ;
        $topic = '/topics'.'/'. strval($user_id)   ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */

    }

    public function commentaryPublication($user_id, $user_name)
    {
        //Notificacion del que crea la solicitud
        /* $title = 'Publicación';
        $body = strval($user_name)  . 'ha comentado tu publicación.' ;
        $topic = '/topics'.'/'. strval($user_id)   ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */

    }

    public function globalNotification($title, $message)
    {
        //Notificacion del que crea la solicitud
        /* $title = strval($title);
        $body = strval($message);
        $topic = '/topics/GLOBAL' ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */

    }

    public function birthdayNotification($name)
    {
        //Notificacion del que crea la solicitud
        /* $title = 'Cumpleaños';
        $body = 'Hoy es el cumpleaños de  '. $name  ;
        $topic = '/topics/GLOBAL' ;
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
 */
    }

    public function birthdaySpecificNotification($user_id, $name)
    {
        //Notificacion del que crea la solicitud
        /* $title = '¡Feliz cumpleaños!';
        $body = 'Felicidades '. $name .' te deseamos un excelente día!' ;
        $topic = '/topics'. '/'.strval($user_id);
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
 */
    }

    public function birthdaySpecificNotificationPost(Request $request)
    {

        //Notificacion del que crea la solicitud
        /* $title = '¡Feliz cumpleaños!';
        $body = 'Felicidades '. $request->name .' te deseamos un excelente día!' ;
        $topic = '/topics'. '/'.strval($request->user_id);
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );

        return back()->with('message', 'Felicitación enviada correctamente');

 */
    }

//NOTIFICACIONES USUARIO A SOPORTE
    public function supportNotification($user,$ticket,$user_id)
    {

       /*  $title = '¡Haz recibido un ticket!';
        $body = '¡'. $user. ' Te ha enviado un ticket ! : ' . $ticket ;
        $topic = '/topics'. '/'.strval($user_id);
        $client = new Client(['verify' => false]);
        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];

        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */
    }

    public function supportEditNotification($user,$ticket,$user_id)
    {

        /*  $title = '¡Edito ticket!';
         $body = '¡'. $user. ' Edito el ticket : ' . $ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function supportFinishedTicket($user,$ticket,$user_id)
    {

         /* $title = '!Ticket Finalizado!';
         $body =  $user. ' Finalizo el ticket : ' . $ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function supportMessage($user,$ticket,$user_id)
    {

        /*  $title = '¡Mensaje!';
         $body = '¡'. $user. ' Envio un mensaje en el ticket : ' . $ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }


    //NOTIFICACIONES SOPORTE A USUARIOS
    public function supportInProgress($ticket,$user_id)
    {

         /* $title = '¡En proceso!';
         $body = ' Ticket : ' . $ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function supportSolution($ticket,$user_id)
    {

         /* $title = '¡Solución recibida!';
         $body = 'En el Ticket : ' . $ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function supportReassignment($ticket,$user_id)
    {

        /*  $title = '¡Ticket!';
         $body = '!' . $ticket .'Te asigno un ticket';
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function supportMessageUser($user,$ticket,$user_id)
    {

        /*  $title = '¡Mensaje!';
         $body = $user.'Te envio un mensaje en el ticket :'.$ticket ;
         $topic = '/topics'. '/'.strval($user_id);
         $client = new Client(['verify' => false]);
         $body = [
             'to' => $topic,
                 'notification' => [
                     'title'=> $title,
                     'body'=> $body,
                 ],
         ];

         $response = $client->request(
             'POST',
             'https://fcm.googleapis.com/fcm/send',
                 [
                 'headers' => [
                     'Content-Type' => 'application/json',
                     'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                 ],
                 'body' => json_encode($body),
             ]
         ); */
    }

    public function reservationNotification($user, $diaInicio, $LInicio,$AnoInicio,$HoraInicio, $diaFin, $LFin,$AnoFin, $HoraFin)
    {
        /* $title ='Reservación de la sala recreativa';
        $body = '¡'. $user. ' ha reservado toda la sala recreativa! La reunión será el día '. $diaInicio . ' de '. $LInicio .' del '. $AnoInicio . ' a las '. $HoraInicio .' y finalizará el día '. 
        $diaFin.' de '. $LFin.' del '. $AnoFin . ' a las '. $HoraFin.'. Por lo tanto en este horario no se podrá reservar la sala ni los cubículos. ';
        $topic = '/topics/COMUNICADOS';
        
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];
        
        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */
        
    }

    public function reservationNotificationedit($user, $diaInicio, $LInicio,$AnoInicio,$HoraInicio, $diaFin, $LFin,$AnoFin, $HoraFin)
    {
        /* $title ='Reservación de la sala recreativa';
        $body = '¡'. $user. ' ha modificado la reservación! La reunión será el día '. $diaInicio . ' de '. $LInicio .' del '. $AnoInicio . ' a las '. $HoraInicio .' y finalizará el día '. 
        $diaFin.' de '. $LFin.' del '. $AnoFin . ' a las '. $HoraFin.'. Por lo tanto en este horario no se podrá reservar la sala ni los cubículos. ';
        $topic = '/topics/COMUNICADOS'; 
        
        $client = new Client(['verify' => false]);

        $body = [
            'to' => $topic,
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ],
        ];
        
        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        ); */
        
    }
}
