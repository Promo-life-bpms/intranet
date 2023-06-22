@component('mail::message')
# ¡Buen día, {{$RH}}!

<h2> {{$dueño}} ha modificado la reunión. </h2>

<h2> información de la reservación es la siguiente: </h2>

La reunión será en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$ubicacion}}".

La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".

<h2> {{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s). </h2>

</h2> La descripción de la reunión es la siguiente: "{{$description}}". </h2>

@endcomponent