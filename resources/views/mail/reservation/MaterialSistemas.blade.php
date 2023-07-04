@component('mail::message')
# ¡Buen día, {{$Sistemas}}!

<h2> {{$dueño}} ha creado una reunión. </h2>

<h2> La información de la reservación es la siguiente: </h2>

La reunión será en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$ubicacion}}".

Dará comienzo el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las 
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

<h2> {{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadProyectores}}" proyector(es). </h2>

<h2> La reunión tiene la siguiente descripción: "{{$description}}". </h2>

@endcomponent