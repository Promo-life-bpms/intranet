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

<div class="contenedor">
    <ul class="access_list">
        <li class="access_item">
            <div class="card" style="width: 200px; height:200px;">
                <img src="..." style="width: 100%; height:100px;  object-fit: cover;" class="card-img-top" alt="imagen">
                <div class="card-body">
                    <h5 class="card-title" style=" white-space: nowrap ;"></h5>
                    <br>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

    </ul>
</div>
@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

<style>
    .access_list>.access_item {
        display: inline-block;
        margin-right: 20px;
    }

    .access_list {
        padding-left: 0;
    }
</style>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop