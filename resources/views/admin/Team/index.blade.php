@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <h1 style="font-size:20px">Solicitud de Servicios de Sistemas y Comunicaciones</h1>
            
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success" role="success">
            {{session('success')}}
        </div>   
        @endif
    

    <form action="{{route('team.createTeamRequest')}}" method="POST">

                {!! Form::open(['route' => 'team.request', 'enctype' => 'multipart/form-data']) !!}
                <h2 style="font-size: 15px;">Datos Generales del Personal de Nuevo Ingreso</h2>
            @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Tipo de usuario: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de usuario']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Nombre: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Fecha Requerida: ') !!}
                                <input type="date" id="fecha" name="fecha" class="form-control">
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                    </div>
    </div>
        {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4']) !!}
</div>
        {!! Form::close()!!}
    </form>
@endsection


