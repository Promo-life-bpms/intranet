@component('mail::message')
# ¡Buen día, {{ $data['user'] }}!
# Es momento de disfrutar tus vacaciones.
De acuerdo a nuestra información, tienes <b>{{$data['days']}}</b> día{{$data['days']>1 ?"s":""}} de vacaciones del periodo anterior que no has disfrutado y que expira{{$data['days']>1 ?"n":""}} el día <b>{{$data['exp']}}</b>.
<br>
<b>¡Te invitamos a tomar tus días antes de esta fecha!</b>
<br>
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
