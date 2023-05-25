<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FirebaseNotificationController extends Controller
{
    public function publication()
    {
        $api_url = 'https://fcm.googleapis.com/fcm/send';
        

        $title ='Titulo de prueba';
        $body = 'Mensaje de prueba';
        $topic = "/topics/PUBLICACIONES";
        
        $client = new Client(['verify' => false]);

        $body = [
            'to' => '/topics/PUBLICACIONES',
                'notification' => [
                    'title'=> $title,
                    'body'=> $body,
                ]
        ];
        
        $response = $client->request(
            'POST',
            'https://fcm.googleapis.com/fcm/send',
                [
                'headers' => [
                    'content-type' => 'application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=AAAAwN4KaL4:APA91bHFXg98RZ-H2YSY2RBoj2atnEYKNX-uR5bFUqAf-bUoHj6HbNBrhb2tNdr8sCIRw4XzNRm8Y5QklFFQz3pd4CU0l59qpcJ8byAa5jPXdtVnU4g8ZbIpYxjZXwrRFW68D5g2KYNH'
                ],
                'body' => json_encode($body),
            ]
        );
        
        var_dump(json_decode($response->getBody()->getContents()));
    }
}
