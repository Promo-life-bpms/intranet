@component('mail::message')
#¡Buen día, {{$receptor_name}}!
<br>
##{{$emisor_name}} ha modificado la reunión, echale un vistazo a los detalles.
<br>
La reunión será en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$locacion}}"
<br>
La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}"
<br>
El motivo de la reunión es el siguiente: "{{$description}}"
<br>
#¡TE ESTAREMOS ESPERANDO, SE PUNTUAL!