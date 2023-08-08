@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$dueño}} ha creado una reunión, por lo cual la sala recreativa y los cubílucos no se podrán reservar en ese horario. </h2>

La información de la reservación es la siguiente:

La reunión dará comienzo el día "{{$diainicio}}" de "{{$mesinicio}}" del presente año a las 
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

<h2> Nota: Solo se permitira el acceso al personal autorizado. </h2>
@endif
@endcomponent