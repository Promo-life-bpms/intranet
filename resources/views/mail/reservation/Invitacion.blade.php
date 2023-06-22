@component('mail::message')
# ¡Buen día, {{$receptor_name}}!

<h2> {{$emisor_name}} te ha invitado a una reunión. </h2>

La reunión será en el/la: "{{$nombre_sala}}" que está ubicada en: "{{$locacion}}".

La reunión dará comienzo: "{{$hora_inicio}}" y finalizará: "{{$hora_fin}}".

<h2> El motivo de la reunión es el siguiente: "{{$description}}". </h2>

<h3> ¡TE ESTAREMOS ESPERANDO, SE PUNTUAL! </h3>

@endcomponent