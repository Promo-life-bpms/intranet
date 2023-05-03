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
                        <th scope="col" style="text-align: center">Categoria</th>
                        <th scope="col" style="text-align: center">Descripción</th>
                        <th scope="col" style="text-align: center">Estado</th>
                        <th scope="col" style="text-align: center">ID de Solicitud</th>
                        <th scope="col" style="text-align: center">Opciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $dato )

                        @if($dato->user_id === auth()->id())
                            <input type="hidden" {{$dato->user_id}}>
                            <tr>    
                                <td style="text-align: center">{{$dato->category}}</td>
                                <td style="text-align: center">{{$dato->description}}</td>

                                <td>
                                    @if ($dato->status == 'Aceptado')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-success">{{$dato->status}}</span>
                                    </div>

                                    
                                        @elseif($dato->status == 'Rechazado')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-danger">{{ $dato->status }}</span>
                                    </div>

                                        @elseif($dato->status == 'Pendiente')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-warning text-dark">{{ $dato->status }}</span>
                                    </div>

                                        @elseif($dato->status == 'Solicitud enviada')
                                    <div class="d-flex justify-content-center">
                                        <span class="badge bg-info text-dark">{{ $dato->status }}</span>
                                    </div>
                                    @endif
                                </td>

                                <td style="text-align: center">{{$dato->id}}</td>

                                <td>
                                    @if ($dato->status == 'Aceptado')
                                        <div class="d-flex justify-content-center">
                                            <a type="button" class="btn btn-primary"  href="{{ route('admin.Team.details', $dato->id)}}">Ver Más</a>
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-center">
                                            <a type="button" class="btn btn-primary{{ $dato->status == 'Pendiente' ? ' disabled' : '' }}"  href="{{ route('admin.Team.details', $dato->id)}}" onclick="{{ $dato->status == 'Pendiente' ? 'return false;' : '' }}">Ver Más</a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endif 
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