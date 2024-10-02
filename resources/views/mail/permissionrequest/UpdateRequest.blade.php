@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}} ha modificado su solicitud de "{{$type_request}}". </h2>

Los días que {{$emisor}} solicitó son: {{$days}}.<br>
Descripción: "{{$details}}"

@component('mail::button', ['url' => $url.'/request/authorize-manager', 'color' => 'blue'])
Revisar permiso
@endcomponent

<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request/authorize-manager'}}">{{$url.'/request/authorize-manager'}}</a>

@endcomponent