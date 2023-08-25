@component('mail::message')
# ¡Buen día !

El ticket {{ $data['ticket_name'] }}  esta por vencer 


@component('mail::button', ['url' => 'https://intranet.promolife.lat/support/solution', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent
