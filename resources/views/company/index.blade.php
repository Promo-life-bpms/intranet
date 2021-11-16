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

<div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
    <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
    <label class="btn btn-outline-primary" for="btncheck1">Promolife</label>

    <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
    <label class="btn btn-outline-primary" for="btncheck2">BH Trade</label>

    <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
    <label class="btn btn-outline-primary" for="btncheck3">Trademarket</label>

    <input type="checkbox" class="btn-check" id="btncheck4" autocomplete="off">
    <label class="btn btn-outline-primary" for="btncheck4">PromoDreams</label>
</div>
<div class="row">
    <div class="col-4">
        <div class="mb-2">
            <br>
            <select class="form-select" aria-label="Default select example">
                <option selected>Seleccionar area</option>
                <option value="1">Ventas</option>
                <option value="2">Sistemas</option>
                <option value="3">Administradores</option>
                <option value="4">Contable</option>
                <option value="5">Marketing</option>
                <option value="6">Desarrollo</option>
            </select>
        </div>
    </div>

    <div class="row">

        <div class="contenedor">
            <ul class="access_list" style="padding: 0;">
                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                <li class="access_item">
                    <div class="card" style="width: 200px; height:210px;">
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" style="width: 100%; height:140px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Nombre</p>
                            <p class="card-text-" style=" white-space: nowrap; margin-bottom:5px;">Puesto</p>
                        </div>
                    </div>
                </li>

                
            </ul>
        </div>
    </div>
</div>

@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">



@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop