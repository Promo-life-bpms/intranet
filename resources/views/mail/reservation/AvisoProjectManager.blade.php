@component('mail::message')
# ¡Buen día, Ing. {{$PM}}!


<h2> {{$dueño}} ha creado una reunión. </h2>

<h2> La información de la reunión es la siguiente: </h2>

Título: "{{$title}}".

La reunión se llevará a cabo en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$ubicacion}}".

Dará comienzo el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las "{{$horainicio}}" y 
finalizará el día "{{$diafin}}" de "{{$mesfin}}" del presente año a las "{{$horafin}}".

{{$dueño}} desea que "{{$engrave}}" se grabe la reunión.

Las personas invitadas a la reunión son: 
"{{$invitados}}".

{{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s) y "{{$cantidadProjectores}}" proyector(es).

<h2> La descripción de la reunión es la siguiente: "{{$description}}". </h2>

@endcomponent