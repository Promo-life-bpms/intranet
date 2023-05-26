
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
{{-- se agrega el nombre de quien recibe el mail --}}
# ¡Buen día {{$data['username']}}!

## Haz recibido un ticket

**Categoria** : {{$data['categoria']}}

**Usuario** : {{ $data['name'] }}

**Problema** : {{ $data['name_ticket'] }}

**Fecha** :{{$data['tiempo']}}
@component('mail::button', ['url' => 'http://intranet.test/soporte/solucion', 'color' => 'red'])
    Ver Ticket
@endcomponent
@endcomponent
