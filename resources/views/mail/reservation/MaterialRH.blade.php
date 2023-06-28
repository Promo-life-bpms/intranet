@component('mail::message')
# ¡Buen día, {{$RH}}!

<h2> {{$dueño}} ha creado una reunión. </h2>

<h2> La información de la reservación es la siguiente: </h2>

La reunión será en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$ubicacion}}".

Dará comienzo el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las "{{$horainicio}}" y 
finalizará el día "{{$diafin}}" de "{{$mesfin}}" del presente año a las "{{$horafin}}".

<h2> {{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s). </h2>

<h2> La descripción de la reunión es la siguiente: "{{$description}}". </h2>

@endcomponent