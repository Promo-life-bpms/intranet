@extends('layouts.app')

@section('dashboard')
    <div class="contenedor-logo">
        <ul class="logos" style="padding-left: 10px;">
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/bhtrade.png') }}" alt="bhtrade"></a> </li>
            <li><a href="#"><img style="max-width: 80px;" src="{{ asset('/img/promolife.png') }}" alt="promolife"></a> </li>
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/promodreams.png') }}" alt="promodreams"></a>
            </li>
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/trademarket.png') }}" alt="trademarket"></a>
            </li>
        </ul>
    </div>

    <div class="row" style=" height:280px; background-color:#E2E2E2;">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($communiquesPrincipal as $item)
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="..." alt="First slide">
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>

    <div class="row" style=" min-height:90px; margin-top:20px;">
        <div class="card" style="width: 100%;">
            <div class="card-body">
                <h5 class="card-title">Actividad</h5>
                <p class="card-text">Descripcion de actividad</p>
            </div>
        </div>

    @stop


    @section('styles')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">

    @stop

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
        </script>
    @stop
