@component('mail::message')
# ¡Buen día Departamento de RRHH!
# {{ $user_name }} ha autorizado una solicitud:
<br>
Colaborador:  {{ $emisor_name }}
<br>
Tipo:  {{ $type }}
<br>

@component('mail::button', ['url' => $url.'/request/authorize-rh', 'color' => 'blue'])
    Revisar
@endcomponent
Si quieres reportar algún error, hacer una pregunta o solicitar una característica nueva, puedes hacerlo desde aquí:
@component('mail::button', ['url' => 'https://forms.gle/YjbAwnELxm8WxRxQ7', 'color' => 'red'])
    Centro de Ayuda
@endcomponent
Gracias,<br>
{{ config('app.name') }}
<hr>
Si tienes problemas para visualizar el botón, puedes hacer click en el siguiente enlace: <a href="{{$url.'/request/authorize-rh'}}">{{$url.'/request/authorize-rh'}}</a>
@endcomponent
