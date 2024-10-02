@component('mail::message')
# ¡Buen día, {{$receptor}}!

<h2> {{$emisor}} ha cancelado su solicitud de "{{$type_request}}". </h2>

El motivo de la cancelación es el siguiente: "{{$observation}}"

@endcomponent