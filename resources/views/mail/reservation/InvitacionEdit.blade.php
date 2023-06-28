@component('mail::message')
# ¡Buen día, {{$receptor_name}}!

<h2> {{$emisor_name}} ha modificado la reunión, echale un vistazo a los detalles. </h2>

La reunión será en el(la): "{{$nombre_sala}}" que está ubicado(a) en: "{{$locacion}}".

Dará comienzo el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las "{{$horainicio}}" y 
finalizará el día "{{$diafin}}" de "{{$mesfin}}" del presente año a las "{{$horafin}}".

<h2> El motivo de la reunión es el siguiente: "{{$description}}". </h2>

<h3> ¡TE ESTAREMOS ESPERANDO, SE PUNTUAL! </h3>

@endcomponent