@component('mail::message')
#¡Buen día, {{$RH}}!
<br>
##{{$dueño}} ha modificado la reunión.
<br>
##La información de la reservación es la siguiente:
<br>
La reunión será en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$ubicacion}}".
<br>
La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".
<br>
##{{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s).
<br>
La descripción de la reunión es la siguiente: "{{$description}}".
<br>