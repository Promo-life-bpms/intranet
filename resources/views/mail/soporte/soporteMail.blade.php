
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
# ¡Buen día, {{ $data['name'] }}!

## Haz recibido un ticket

Usuario : {{ $data['email'] }}

Problema : {{ $data['name_ticket'] }}

{{$data['tiempo']}}

@endcomponent
