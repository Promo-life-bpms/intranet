
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
# ¡Buen día {{$data['username']}}!

## El ticket del usuario
 {{ $data['name'] }}

con el problema : {{ $data['name_ticket'] }}

Ha sido : **{{ $data['status'] }}**.


@endcomponent
