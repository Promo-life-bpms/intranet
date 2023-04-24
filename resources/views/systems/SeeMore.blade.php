@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <h1 style="font-size:20px">Cambio de Estado de Solicitud</h1>
            
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success" role="success">
            {{session('success')}}
        </div>   
        @endif

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-directory">
                    <thead>
                        <tr>
                            <th scope="col" style="text-align: center">ID de Usuario</th>
                            <th scope="col" style="text-align: center">Nombre</th>
                            <th scope="col" style="text-align: center">Categoria</th>
                            <th scope="col" style="text-align: center">Descripción</th>
                            <th scope="col" style="text-align: center">Estado</th>
                            <th scope="col" style="text-align: center">ID de Solicitud</th>
                            <th scope="col" style="text-align: center">Fecha de Solicitud</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($see_more as $see )
                        <tr>
                            <td style="text-align: center">{{$see->user_id}}</td>
                            <td>{{$see->user->name.' '. $see->user->lastname}}</td>
                            <td>{{$see->category}}</td>
                            <td>{{$see->description}}</td>
                            <td>{!! Form::select('category', ['Pendiente' => 'Pendiente', 'Aprobado' => 'Aprobado', 'Rechazado' => 'Rechazado'], 'estado', ['class' => 'form-control','placeholder' => 'Selecciona el Estado']) !!}</td>
                            <td style="text-align: center">{{$see->id}}</td>
                            <td style="text-align: center">{{$see->updated_at}}</td> 
                            <td>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {!! Form::submit('ACTUALIZAR ESTADO', ['class' => 'btnCreate mt-4']) !!}
        </div>

            <style>
                table {
                font-size: 66.1%;
                }

                .form-control{
                    font-size: 10px;
                    padding: 10px;
                    width:150px;
                }
            </style>
@endsection