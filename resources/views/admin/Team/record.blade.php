@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Historial de Solicitudes</h1>
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
                    @foreach ($datos as $dato)
                        <tr>    
                            <td style="text-align: center">{{$dato->id}}</td>
                            <td style="text-align: center">{{$dato->user->name.' '. $dato->user->lastname}}</td>
                            <td>
                                @if ($dato->status == 'Aprobada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-success">{{$dato->status}}</span>
                                    </div>
    
                                    @elseif($dato->status == 'Rechazada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-danger">{{ $dato->status }}</span>
                                    </div>
    
                                    @elseif($dato->status == 'Preaprobada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-warning text-dark">{{ $dato->status }}</span>
                                    </div>
    
                                    @elseif($dato->status == 'Solicitud Creada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-info text-dark">{{ $dato->status }}</span>
                                    </div>
                                @endif
                            </td>
                            <td style="text-align: center">{{$dato->created_at}}</td>
                            <td style="text-align: center">
                                <a href="{{ route('admin.Team.details', $dato->id)}}" class="btn btn-primary">Ver más</a>
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