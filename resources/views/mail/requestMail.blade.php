@component('mail::message')
# Solicitud aprobada

### Su solicitud ha sido aprobada.

Detalles: 
<table>
   <tr>
       <td>Tipo</td>
       <td>{{$mailInfo->type_request}}</td>
   </tr>
   <tr>
        <td>Pago</td>
        <td>{{$mailInfo->payment}}</td>
    </tr>
    <tr>
        <td>Motivo</td>
        <td>{{$mailInfo->reason}}</td>
    </tr>
</table>

<br>
Para mas detalles, ingrese a la plataforma haciendo <a href="https://intranet.promolife.lat/">click aqui</a>

@endcomponent