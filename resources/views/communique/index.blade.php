@extends('layouts.app')

@section('content')
    <div class="card-body">
        <div class="row" style=" height:280px; background-color:#E2E2E2; margin-bottom:20px;">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active  overflow-hidden  ">
                        <img style="object-fit: cover" class="w-75 d-block"
                            src="https://th.bing.com/th/id/R.69271e227f390f1b19df454cf37e5ce9?rik=rggFzCRyBf8D%2fQ&riu=http%3a%2f%2fprepa7.computounam.mx%2fwp-content%2fuploads%2f2020%2f06%2fcovid19-5-1024x1024.jpg&ehk=9QFlEmamL2pJfy10ItuevVEt3k08ZbXzbjK8OympE9s%3d&risl=&pid=ImgRaw&r=0">
                    </div>

                    <div class="carousel-item overflow-hidden  ">
                        <img style="object-fit: cover"
                            src="https://e00-marca.uecdn.es/assets/multimedia/imagenes/2020/04/10/15864870366308.jpg"
                            class="w-75 d-block" alt="infografia">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Anterior</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </div>
        </div>

        @foreach ($communiquesPrincipal as $item)
            <div class="row" style=" min-height:90px;">
                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@stop


@section('styles')
    <style>
        .carousel-inner img {
            max-width: 100%;
            height: 280px;
            object-fit: contain;
            object-position: center;
        }

    </style>
@stop
