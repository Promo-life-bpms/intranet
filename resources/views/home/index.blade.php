@extends('layouts.app')

@section('dashboard')
    <div class="row">
        <div class="col-md-8">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-6">
                        @if ($communiques)
                            @foreach ($communiques as $communique)
                                <img class="img-fluid rounded" src="{{ asset($communique->images) }}" alt="" srcset="">
                            @endforeach
                        @endif
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>Ultimos comunicados</h4>
                            <a href="{{ route('communiques.index') }}" class="btn btn-primary btn-sm">Todos</a>
                        </div>
                        <hr>
                        <ul class="list-group">
                            @if ($communiques)
                                @foreach ($communiques as $communique)
                                    <li class="list-group-item">
                                        {{ $communique->title }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>CEO Message</h4>
                <hr>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque eligendi magnam sit inventore qui
                    laudantium repellendus numquam saepe eaque sed. Inventore commodi pariatur facere quae ducimus
                    laudantium impedit veniam molestias.</p>
                <span class="text-left">-David Levy</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Cumpleaños</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Vacaciones</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Aniversarios</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Empleado del Mes</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-3">
                <h4>Calendario</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Nuevos ingresos</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
@stop

@section('styles')
@stop
