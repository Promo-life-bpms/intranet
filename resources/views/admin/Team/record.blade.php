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
                        <th scope="col" style="text-align: center">N° Usuario</th>
                        <th scope="col" style="text-align: center">Categoria</th>
                        <th scope="col" style="text-align: center">Descripción</th>
                        <th scope="col" style="text-align: center">Estado</th>
                        <th scope="col" style="text-align: center">ID</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($datos as $dato )

                        @if($dato->user_id === auth()->id())
                            <input type="hidden" {{$dato->user_id}}>
                            <tr>
                                <th style="text-align: center">{{$dato->user_id}}</th>             
                                <th>{{$dato->category}}</th>
                                <th>{{$dato->description}}</th>
                                <th>{{$dato->status}}</th>
                                <th>{{$dato->id}}</th>
                            </tr>
                        @endif 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection