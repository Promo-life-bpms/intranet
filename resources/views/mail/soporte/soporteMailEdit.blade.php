@component('mail::message')
# ¡Buen día !

## {{$data['name']}} {{$data['last_name']}} edito su ticket !

**Problema** : {{ $data['name_ticket'] }}

## Revisalo para ver sus cambios.

@component('mail::button', ['url' => 'https://intranet.promolife.lat/support/solution', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent