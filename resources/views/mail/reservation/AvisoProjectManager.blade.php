@component('mail::message')

# ¡Buen día, Ing. {{$PM}}!

<h2> {{$dueño}} {{$lastname_dueño}} ha creado una reunión. </h2>

<h2> La información de la reunión es la siguiente: </h2>

Título: "{{$title}}".

La reunión se llevará a cabo en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$ubicacion}}".

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

{{$dueño}} desea que "{{$engrave}}" se grabe la reunión.

Las personas invitadas a la reunión son: 
"{{$invitados}}".

{{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s) y "{{$cantidadProjectores}}" proyector(es).

<h2> La descripción de la reunión es la siguiente: "{{$description}}". </h2>

@endcomponent