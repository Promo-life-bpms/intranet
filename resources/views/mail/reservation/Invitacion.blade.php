@component('mail::message')
# ¡Buen día, {{$receptor_name}}!

<h2> {{$emisor_name}} te ha invitado a una reunión. </h2>

La reunión será en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$locacion}}".

La reunión será el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las
@if (intval(substr($horainicio, 0, 2)) >= 12)
"{{$horainicio}} P.M."
@else
"{{$horainicio}} A.M."
@endif 
y finalizará el día "{{$diafin}}" de "{{$mesfin}}" del presente año a las 
@if (intval(substr($horafin, 0, 2)) >= 12)
"{{$horafin}} P.M."
@else
"{{$horafin}} A.M."
@endif

<h2> El motivo de la reunión es el siguiente: "{{$description}}". </h2>

<h3> TE ESTAREMOS ESPERANDO, ¡SE PUNTUAL! </h3>

@endcomponent