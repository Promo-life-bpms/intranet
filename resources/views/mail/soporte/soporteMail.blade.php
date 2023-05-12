
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
# ¡Buen día!

## Haz recibido un ticket

**Usuario** : {{ $data['name'] }}

**Problema** : {{ $data['name_ticket'] }}

{{$data['tiempo']}}
@component('mail::button', ['url' => 'https://intranet.promolife.lat/soporte/solucion', 'color' => 'blue'])
    Ver Ticket
@endcomponent
@endcomponent
