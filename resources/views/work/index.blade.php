@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Trellos disponibles</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card shadow p-3 mb-5 rounded" style="width: 200px; height:210px;">
                    <img src="https://media.istockphoto.com/vectors/number-1-logo-colored-paint-one-icon-with-drips-dripping-liquid-art-vector-id969333104?k=6&m=969333104&s=170667a&w=0&h=KwiWuJM7jYovCeVUj_6ba9JjGSYsKWicJfw5HYh9z70="
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Equipo 1</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow p-3 mb-5 rounded" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2017/06/10/07/18/list-2389219_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Tareas Semanales</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow p-3 mb-5 rounded" style="width: 200px; height:210px;">
                    <img src="https://cdn.pixabay.com/photo/2020/04/04/03/42/space-5000696_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title" style=" white-space: nowrap; margin-bottom:5px;">Avances Proyecto</h5>
                        <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <button class="btnCreate"> CREAR NUEVO </button>
@stop
