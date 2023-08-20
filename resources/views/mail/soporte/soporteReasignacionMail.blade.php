@component('mail::message')
# ¡Buen día !

## {{$data['name']}} te ha reasignado un ticket!

**Usuario:** {{$data['user_ticket']}}

**Categoria:** {{$data['ticket_category']}}

**Departamento:** {{$data['user_department']}}

**Problema** : {{ $data['name_ticket'] }}

## Revisalo para darle solución.

@component('mail::button', ['url' => 'https://intranet.promolife.lat/support/solution', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent