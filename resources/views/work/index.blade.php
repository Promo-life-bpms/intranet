@extends('layouts.app')

@section('dashboard')

<div class="contenedor-logo">
    <ul class="logos" style="padding-left: 10px;">
        <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/bhtrade.png')}}" alt="bhtrade"></a> </li>
        <li><a href="#"><img style="max-width: 80px;" src="{{asset('/img/promolife.png')}}" alt="promolife"></a> </li>
        <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/promodreams.png')}}" alt="promodreams"></a> </li>
        <li><a href="#"><img style="max-width: 50px;" src="{{asset('/img/trademarket.png')}}" alt="trademarket"></a> </li>
    </ul>
</div>

<div class="container">
    
    <ul class="access_list">
    <h1>Espacios de Trabajo</h1>
    <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://media.istockphoto.com/vectors/number-1-logo-colored-paint-one-icon-with-drips-dripping-liquid-art-vector-id969333104?k=6&m=969333104&s=170667a&w=0&h=KwiWuJM7jYovCeVUj_6ba9JjGSYsKWicJfw5HYh9z70=" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Equipo 1</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://cdn.pixabay.com/photo/2017/06/10/07/18/list-2389219_960_720.png" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Tareas Semanales</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://cdn.pixabay.com/photo/2020/04/04/03/42/space-5000696_960_720.png" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Avances Proyecto</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <button class="btnCreate"> CREAR NUEVO </button>     
    </ul>
</div>


@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop