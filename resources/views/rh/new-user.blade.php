@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3 class="separator">Generar alta</h3> 
    </div>
    <div class="card-body">
    <div class="d-flex flex-row justify-content-between" >
        <h6>Lista de postulantes disponibles</h6>
        <a class="btn btn-success" href="{{ route('rh.createPostulant') }}">
            <i style="margin-right:8px" class="fa fa-plus" aria-hidden="true"></i>
            Agregar nuevo
        </a>
    </div>
       
   
    </div>
@stop
