@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}} ha aprobado la solicitud de "{{$type_request}}" de {{$user}}. </h2>

Los días que {{$user}} solicitó son: {{$days}}.<br>
Descripción: "{{$details}}"

@component('mail::button', ['url' => $url.'/request/authorize-rh', 'color' => 'blue'])
Revisar permiso
@endcomponent

<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request/authorize-rh'}}">{{$url.'/request/authorize-rh'}}</a>

@endcomponent