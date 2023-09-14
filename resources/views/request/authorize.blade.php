@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Solicitudes recibidas para autorización</h3>
            </div>
        </div>
        <div class="card-body">
            @livewire('list-request')
        </div>
    </div>

@stop

@section('styles')
    <style>
        .nav-link {
            font-size: 20px;
        }
    </style>
@stop


@section('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stop
