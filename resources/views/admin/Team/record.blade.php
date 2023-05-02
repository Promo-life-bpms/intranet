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
                        <td scope="col" style="text-align: center">Categoria</td>
                        <td scope="col" style="text-align: center">Descripción</td>
                        <td scope="col" style="text-align: center">Estado</td>
                        <td scope="col" style="text-align: center">ID de Solicitud</td>
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
                                            <span class="badge bg-success">{{$dato->status}}</span>
                                            @elseif($dato->status == 'Rechazado')
                                            <span class="badge bg-danger">{{ $dato->status }}</span>
                                            @elseif($dato->status == 'Pendiente')
                                            <span class="badge bg-warning text-dark">{{ $dato->status }}</span>
                                            @elseif($dato->status == 'Solicitud enviada')
                                            <span class="badge bg-info text-dark">{{ $dato->status }}</span>
                                        @endif
                                    </td>

                                <td style="text-align: center">{{$dato->id}}</td>
                            </tr>
                        @endif 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection