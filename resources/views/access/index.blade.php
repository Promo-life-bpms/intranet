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
                <img src="https://th.bing.com/th/id/R.c456f6643044a4ce3b91f10dce20e355?rik=5tQkZX3Y9QjnFQ&pid=ImgRaw&r=0" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">ODDO</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://th.bing.com/th/id/R.8503059f3dead01b371aa314d15447e9?rik=KJB45xNPCjBNrw&riu=http%3a%2f%2fprevencionar.com.mx%2fmedia%2fsites%2f3%2f2015%2f07%2fnom.png&ehk=GdWpvMUY9ftr8OQUuCFuBV0zAt6zKdH6LJj3IGZ5kX4%3d&risl=&pid=ImgRaw&r=0&sres=1&sresct=1" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">NOM 035</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://cdn.pixabay.com/photo/2017/01/05/19/10/cart-1956097_960_720.png" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Cotizador Web</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://cdn.pixabay.com/photo/2016/06/15/15/02/info-1459077__340.png" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Help Desk</h5>
                    <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                </div>
            </div>
        </li>

        <li class="access_item">
            <div class="card" style="width: 200px; height:210px;">
                <img src="https://cdn.pixabay.com/photo/2017/05/15/23/48/survey-2316468_960_720.png" style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                <div class="card-body" style="padding-top:0; padding-bottom:0">
                    <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Encuesta 360</h5>
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

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop