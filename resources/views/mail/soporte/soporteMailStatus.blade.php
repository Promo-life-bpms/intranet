
{{-- Aqui se envia el correo del ticket --}}
@component('mail::message')
# ¡Buen día, {{ $data['name'] }}!

## El ticket del usuario
 {{ $data['email'] }}

con el problema : {{ $data['name_ticket'] }}

Ha sido : **{{ $data['status'] }}**.

@endcomponent
