@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}} ha rechazado tu solicitud de "{{$type_request}}". </h2>

El motivo de la cancelación es el siguiente: "{{$observation}}"

@component('mail::button', ['url' => $url.'/request', 'color' => 'blue'])
Revisar solicitud
@endcomponent

<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request'}}">{{$url.'/request'}}</a>

@endcomponent