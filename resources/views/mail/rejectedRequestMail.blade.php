@component('mail::message')

# ¡Hola {{ $mailInfo['name'] }}!

## Tu solicitud no ha cumplido con los requisitos para ver aprobada.

Te recomendamos verificar los datos y enviarla nuevamente.

<br>

Detalles: 
<table>
   <tr>
       <td>Tipo</td>
       <td>{{$mailInfo['type_request'] }}</td>
   </tr>
   <tr>
        <td>Pago</td>
        <td>{{$mailInfo['payment']}}</td>
    </tr>
    <tr>
        <td>Motivo</td>
        <td>{{$mailInfo['reason']}}</td>
    </tr>
    <tr>
        <td>Dias</td>
        <td>{{$mailInfo['days']}}</td>
    </tr>
    @if ($mailInfo['start']!=null)
    <tr>
        <td>Hora de salida</td>
        <td>{{$mailInfo['start']}}</td>
    </tr>
    @if ($mailInfo['end']!=null)
    <tr>
        <td>Hora de reingreso</td>
        <td>{{$mailInfo['end']}}</td>
    </tr>
    @endif
    @endif

    
    
</table>

<br>
Para mas información, ingrese a la plataforma haciendo <a href="https://intranet.promolife.lat/">click aqui</a>

@endcomponent