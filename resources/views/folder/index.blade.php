@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Mi espacio</h3>
    </div>
    <div class="card-body">
        <div class="card" style="width: 200px; height:210px;">
            <img src="https://th.bing.com/th/id/R.84001534ea71965d4dff89d54c006681?rik=ovcZDJdrcFHrWg&pid=ImgRaw&r=0"
                style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
            <div class="card-body" style="padding-top:0; padding-bottom:0">
                <h5 class="card-title" style="width:90%;  white-space: nowrap; margin-bottom:5px;">Carpeta Drive
                </h5>
                <a href="" style="width: 100%" class="btn btn-primary with">ABRIR</a>
            </div>
        </div>
    </div>
@stop
