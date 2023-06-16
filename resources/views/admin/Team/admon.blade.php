@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Administración de Solicitudes</h1>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col" style="text-align: center">ID de Solicitud</th>
                        <th scope="col" style="text-align: center">Solicitante</th>
                        <th scope="col" style="text-align: center">Estado</th>
                        <th scope="col" style="text-align: center">Fecha de creación</th>
                        <th scope="col" style="text-align: center">Opciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<style>
    table {
    font-size: 66.1%;
    }

    .btn {
    font-size: 10px;
    }
</style>
@endsection