@extends('layouts.app')

@section('dashboard')
<div class="contenedor-logo">
    <ul class="logos" style="padding-left: 10px; ">
        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/bhtrade.png') }}" alt="bhtrade"></a> </li>
        <li><a href="#"><img style="max-width: 80px;" src="{{ asset('/img/promolife.png') }}" alt="promolife"></a> </li>
        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/promodreams.png') }}" alt="promodreams"></a>
        </li>
        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/trademarket.png') }}" alt="trademarket"></a>
        </li>
    </ul>
</div>

<div class="row" style=" height:280px; background-color:#E2E2E2; margin-bottom:20px;">
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" style="object-fit: cover;">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block w-100" src="https://th.bing.com/th/id/R.69271e227f390f1b19df454cf37e5ce9?rik=rggFzCRyBf8D%2fQ&riu=http%3a%2f%2fprepa7.computounam.mx%2fwp-content%2fuploads%2f2020%2f06%2fcovid19-5-1024x1024.jpg&ehk=9QFlEmamL2pJfy10ItuevVEt3k08ZbXzbjK8OympE9s%3d&risl=&pid=ImgRaw&r=0">
            </div>

            <div class="carousel-item">
                <img src="https://e00-marca.uecdn.es/assets/multimedia/imagenes/2020/04/10/15864870366308.jpg" class="d-block w-100" alt="infografia">
            </div>
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

@foreach ($communiquesPrincipal as $item)
<div class="row" style=" min-height:90px;">
    <div class="card" style="width: 100%;">
        <div class="card-body">
            <h5 class="card-title">{{$item -> title}}</h5>
            <p class="card-text">{{$item ->description}}</p>
        </div>
    </div>
</div>
@endforeach
@stop


@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{ asset('/css/styles.css') }}" rel="stylesheet">
<style>
    .carousel-inner img {
        max-width: 100%;
        height: 280px;
        object-fit: contain;
        object-position: center;
    }
</style>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
@stop