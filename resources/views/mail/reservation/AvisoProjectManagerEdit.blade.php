@component('mail::message')
#¡Buen día, Ing. {{$PM}}!
<br>
##{{$dueño}} ha modificado la reunión.
<br>
##La información del evento es la siguiente:
<br>
Título: "{{$title}}"
<br>
La reunión se llevará a cabo en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$ubicacion}}"
<br>
Comenzará: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".
<br>
Las personas invitadas a la reunión son:
                        "{{$invitados}}"
<br>
{{$dueño}} solicito el siguiente material en la cantidad de "{{$cantidadSillas}}" silla(s) y "{{$cantidadProjectores}}" proyector(es).
<br>
##La descripción de la reunión es la siguiente: "{{$description}}"
<br>