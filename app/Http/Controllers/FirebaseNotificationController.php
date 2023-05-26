<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FirebaseNotificationController extends Controller
{
    public function publication($user, $message)
    {
        $api_url = 'https://fcm.googleapis.com/fcm/send';
        

        $title ='Nueva Publicación';
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
        );
        
        var_dump(json_decode($response->getBody()->getContents()));
    }

    public function communique($title_message,$message)
    {
        $api_url = 'https://fcm.googleapis.com/fcm/send';
        
        $title = $title_message;
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
        
        var_dump(json_decode($response->getBody()->getContents()));
    }
}
