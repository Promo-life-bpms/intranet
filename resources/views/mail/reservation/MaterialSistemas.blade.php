@component('mail::message')
# ¡Buen día, {{$Sistemas}}!

<h2> {{$dueño}} ha creado una reunión. </h2>

<h2> La información de la reservación es la siguiente: </h2>

La reunión será en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$ubicacion}}".

La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".

<h2> {{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadProyectores}}" proyector(es). </h2>

<h2> La reunión tiene la siguiente descripción: "{{$description}}". </h2>

@endcomponent