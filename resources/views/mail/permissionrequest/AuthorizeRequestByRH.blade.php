@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}}, colaborador(a) del departamento de Recursos Humanos, ha aprobado tu solicitud de "{{$type_request}}". </h2>

Los días que solicitastes son: {{$days}}. <br>
Con el motivo: "{{$details}}".

@component('mail::button', ['url' => $url.'/request', 'color' => 'blue'])
Revisar permiso
@endcomponent

<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request'}}">{{$url.'/request'}}</a>

@endcomponent