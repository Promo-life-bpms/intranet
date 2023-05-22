
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
{{-- se agrega el nombre de quien recibe el mail --}}
# ¡Buen día!

## Haz recibido un ticket

**Usuario** : {{ $data['name'] }}

**Problema** : {{ $data['name_ticket'] }}

**Fecha**{{$data['tiempo']}}
@component('mail::button', ['url' => 'http://intranet.test/soporte/solucion', 'color' => 'blue'])
    Ver Ticket
@endcomponent
@endcomponent
