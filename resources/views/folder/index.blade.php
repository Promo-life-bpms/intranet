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
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://th.bing.com/th/id/R.84001534ea71965d4dff89d54c006681?rik=ovcZDJdrcFHrWg&pid=ImgRaw&r=0" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style="width:90%;  white-space: nowrap; margin-bottom:5px;">Carpeta Drive</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>
      
    </ul>
</div>

@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<style>
    .access_list>.access_item {
  display: inline-block;
  margin-right: 20px;
}

.card{
  padding-left: 0;
  border-radius: 10px;
}

.btn-group{
  display: flex;
  justify-content: center;
}
</style>
@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop