@component('mail::message')
# ¡Buen día {{$RH}}!
<h2> {{$name}} ha creado una nueva solicitud, para visualizar las solicitudes creadas da clic en el siguiente botón.</h2>

@component('mail::button', ['url' => 'http://127.0.0.1:8000/admon-request', 'color' => 'green'])
    Ver administración de solicitudes
@endcomponent
@endcomponent
