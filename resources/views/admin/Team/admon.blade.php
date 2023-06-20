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

                <tbody>
                    @foreach ($admon_requests as $admon_request)
                        <tr>    
                            <td style="text-align: center">{{$admon_request->id}}</td>
                            <td style="text-align: center">{{$admon_request->name}}</td>
                            <td>
                                @if ($admon_request->status == 'Aprobada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-success">{{$admon_request->status}}</span>
                                    </div>
    
                                    @elseif($admon_request->status == 'Rechazada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-danger">{{ $admon_request->status }}</span>
                                    </div>
    
                                    @elseif($admon_request->status == 'Preaprobada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-warning text-dark">{{ $admon_request->status }}</span>
                                    </div>
    
                                    @elseif($admon_request->status == 'Solicitud Creada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-info text-dark">{{ $admon_request->status }}</span>
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