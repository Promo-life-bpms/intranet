@component('mail::message')
# ¡Buen día, {{ $receptor_name }}!
# {{ $emisor_name }} ha realizado un nueva solicitud:
Tipo:  {{ $type }}

@component('mail::button', ['url' => $url.'/request/authorize-manager', 'color' => 'blue'])
    Revisar
@endcomponent
Si quieres reportar algún error, hacer una pregunta o solicitar una característica nueva, puedes hacerlo desde aquí:
@component('mail::button', ['url' => 'https://forms.gle/YjbAwnELxm8WxRxQ7', 'color' => 'red'])
    Centro de Ayuda
@endcomponent
Gracias,<br>
{{ config('app.name') }}
<hr>
Si tienes problemas para visualizar el boton, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request/authorize-manager'}}">{{$url.'/request/authorize-manager'}}</a>
@endcomponent
