@extends('layouts.app')

@section('dashboard')
    <div class="px-5 text-center">
        <h2>Promo The New York Times</h2>
        <hr>
        @php
            $carbon = new \Carbon\Carbon();
            $date = $carbon->now();
            $date = $date->format('d-m-Y');
        @endphp
        <h4 class="text-center">
            {{ $date }}
        </h4>
        <hr>
        <h3>{{ $communique->title }}</h3>
        <div class="row">
            <div class="col-md-4">
                <h4>Vacaciones</h4>
            </div>
            <div class="col-md-4">
                <img src="{{ asset('') . $communique->images }}" alt="" srcset="" class="img-fluid rounded shadow-sm">
            </div>
            <div class="col-md-4">
                <h4>Cumpleaños</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <h3>Aniversarios</h3>
            </div>
            <div class="col-md-8">
                <h3>Segundo comunicado</h3>
            </div>
        </div>
        <img class="promonews" style="width:90%; " src="{{ asset('/img/diseno.jpeg') }}" alt="diseno">
    </div>
@stop

@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noticia+Text:wght@700&display=swap" rel="stylesheet">
    <style>
        h2 {
            font-family: 'Noticia Text', serif;
            font-size: 3.5rem;
        }

        h3 {
            font-family: 'Noticia Text', serif;
            font-size: 2.5rem;
        }

        h4 {
            font-family: 'Noticia Text', serif;
            font-size: 1.5rem;
        }

        hr:not([size]) {
            height: 2px;
            background-color: #000;
        }

        #main {
            background-color: #DDDDDD;
        }

    </style>
@stop
