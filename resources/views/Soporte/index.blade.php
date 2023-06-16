@extends('layouts.app')

@section('content')
{{-- <livewire:..soporte/listado-tickets-component /> --}}
@livewire('listado-tickets-component')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
