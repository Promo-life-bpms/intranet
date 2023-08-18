@component('mail::message')
# ¡Buen día !

## Haz recibido un ticket

**Categoría** : {{$data['categoria']}}

**Usuario** : {{ $data['name'] }} {{ $data['lastname'] }}
{{-- Falta agregar el departamento --}}
**Problema** : {{ $data['name_ticket'] }}

**Fecha** : {{$data['tiempo']}}

@component('mail::button', ['url' => 'https://intranet.promolife.lat/support/solution', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent
