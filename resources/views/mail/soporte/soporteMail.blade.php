@component('mail::message')
# ¡Buen día !

## Haz recibido un ticket

**Categoría** : {{$data['categoria']}}

**Usuario** : {{ $data['name'] }}

**Problema** : {{ $data['name_ticket'] }}

**Fecha** : {{$data['tiempo']}}

@component('mail::button', ['url' => 'https://intranet.promolife.lat/soporte/solucion', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent
