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

    <div class="contenedor">
        <ul class="access_list">
            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Reglamento</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>

            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Politicas</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>

            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Confidencialidad</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>

            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Procedimientos</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>

            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Ventas</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>

            <li class="access_item">
                <div class="card" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Entregas</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@stop
