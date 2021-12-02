@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Ultimos comunicados</h3>
    </div>
    <div class="card-body">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                @foreach ($communiquesPrincipal as $principal)
                    <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                        <img src="{{ asset($principal->images) }}" class="d-block w-100"
                            style="max-height: 400px;object-fit: cover;" alt="...">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>{{ $principal->title }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <br>
        <h3>Todos los comunicados</h3>
        <ul class="list-group list-group-flush">
            @foreach ($communiques as $item)
                <li class="list-group-item">
                    <h5 class="card-title">{{ $item->title }}</h5>
                </li>
            @endforeach
        </ul>
        <div class="d-block text-center">
            {{ $communiques->links() }}
        </div>
    </div>
@stop


@section('styles')
@stop
