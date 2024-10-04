@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}} ha aprobado tu solicitud de "{{$type_request}}". </h2>

La solicitud aprobada tiene la siguiente descripción: "{{$details}}"

Recuerda que aún debes esperar la confirmación de Recursos Humanos,
y que la aprobación de tu jefe directo no garantiza la aprobación de RH.

@component('mail::button', ['url' => $url.'/request', 'color' => 'blue'])
Revisar solicitud
@endcomponent

<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request'}}">{{$url.'/request'}}</a>

@endcomponent