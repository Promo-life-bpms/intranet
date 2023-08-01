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
                        <th scope="col" style="text-align: center">Fecha de Creación</th>
                        <th scope="col" style="text-align: center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($admon_requests as $admon_request)
                        <tr>    
                            <td style="text-align: center">{{$admon_request->id}}</td>
                            <td style="text-align: center">{{$admon_request->user->name.' '. $admon_request->user->lastname}}</td>
                            <td>
                                @if ($admon_request->status == 1)
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-success">Aprobada</span>
                                    </div>
    
                                    @elseif($admon_request->status == 2)
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-danger">Rechazada</span>
                                    </div>
    
                                    @elseif($admon_request->status == 0)
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-info text-dark">Solicitud Creada</span>
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center">{{$admon_request->created_at}}</td>
                            <td style="text-align: center">
                                <a href="{{ route('admin.Team.information', $admon_request->id)}}" class="btn btn-primary">Ver más</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
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