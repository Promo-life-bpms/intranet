@component('mail::message')
#¡Buen día, {{$Sistemas}}!
<br>
##{{$dueño}} ha modificado la reunión.
<br>
##La información del evento es la siguiente:
<br>
La reunión será en: "{{$nombre_sala}}" que está ubicada en: "{{$ubicacion}}".
<br>
La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".
<br>
##{{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadProyectores}}" proyector(es).
<br>
La reunión tiene la siguiente descripción: "{{$description}}".
<br>