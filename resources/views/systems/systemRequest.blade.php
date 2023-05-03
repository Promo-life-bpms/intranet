@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Estado actual de Solicitud</h1>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="table-directory">
                <thead>
                    <tr>
                        <th scope="col" style="text-align: center">Nombre</th>
                        <th scope="col" style="text-align: center">Categoria</th>
                        <th scope="col" style="text-align: center">Descripción</th>
                        <th scope="col" style="text-align: center">Estado</th>
                        <th scope="col" style="text-align: center">ID de Solicitud</th>
                        <th scope="col" style="text-align: center">Fecha de Solicitud</th>
                        <th scope="col" style="text-align: center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($systems_request as $systems )
                    <tr>
                        <td style="text-align: center">{{$systems->user->name.' '. $systems->user->lastname}}</td>
                        <td style="text-align: center">{{$systems->category}}</td>
                        <td style="text-align: center">{{$systems->description}}</td>

                        <td>
                            @if ($systems->status == 'Aceptado')
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-success">{{$systems->status}}</span>
                                </div>

                                @elseif($systems->status == 'Rechazado')
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-danger">{{ $systems->status }}</span>
                                </div>

                                @elseif($systems->status == 'Pendiente')
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-warning text-dark">{{ $systems->status }}</span>
                                </div>

                                @elseif($systems->status == 'Solicitud enviada')
                                <div class="d-flex justify-content-center">
                                    <span class="badge bg-info text-dark">{{ $systems->status }}</span>
                                </div>
                            @endif
                        </td>
                        
                        <td style="text-align: center">{{$systems->id}}</td>
                        <td style="text-align: center">{{$systems->updated_at}}</td> 
                        <td>
                        <a type="button" class="btn btn-primary"  href="{{ route('systems.show', $systems->id)}}">Ver Más</a>
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

        .btn{
            font-size: 10px;
        }
    </style>
@endsection

            