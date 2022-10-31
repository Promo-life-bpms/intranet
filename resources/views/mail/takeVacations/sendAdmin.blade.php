@component('mail::message')
# ¡Buen día, Administrador!
# Se ha enviado un recordatorio de vacaciones.
<br>
# Correos enviados
@component('mail::table')
| Usuario          | Expiracion         | Dias     |
| ---------------- |:------------------:| --------:|
@foreach ($data[1] as $item)
| {{$item['user']}} | {{$item['exp']}} | {{$item['days']}}     |
@endforeach
@endcomponent
# Errores
@component('mail::table')
| Usuario          | Mensaje         |
| ---------------- |:------------------:|
@foreach ($data[0] as $item)
| {{$item['user']}} | {{$item['msg']}} |
@endforeach
@endcomponent
Puedes obtener mas información de tus vacaciones desde nuestra Intranet Corporativa:
@component('mail::button', ['url' => $url, 'color' => 'blue'])
    Acceder
@endcomponent
Si deseas alguna aclaración acerca de este correo y su contenido puedes acudir a recursos humanos.
<br>

Si quieres reportar algún error, hacer una pregunta o solicitar una característica nueva, puedes hacerlo desde aquí:
@component('mail::button', ['url' => 'https://forms.gle/YjbAwnELxm8WxRxQ7', 'color' => 'red'])
    Centro de Ayuda
@endcomponent
Gracias,<br>
{{ config('app.name') }}
<hr>
Si tienes problemas para visualizar el boton, puedes hacer click en el siguiente enlace: <a href="{{$url}}">{{$url}}</a>
@endcomponent
