@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Agregar Manual</h3>
        </div>

    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'manual.store','enctype' => 'multipart/form-data']) !!}

        <div class="row">
            <div class="col-md-6 form-group">
                {!! Form::label('name', 'Nombre del manual') !!}
                {!! Form::text('name', null , ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del manual']) !!}
                @error('name')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
                @enderror
            </div>

            <div class="col-md-6 form-group">
                {!! Form::label('file', 'Archivo') !!}
                {!! Form::file('file', ['class' => 'form-control', 'placeholder' => 'Ingrese el archivo']) !!}
                @error('file')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col form-group">
                {!! Form::label('img', 'Imágen (opcional)') !!}
                {!! Form::file('img', ['class' => 'form-control', 'placeholder' => 'Ingrese el archivo']) !!}
            </div>
        </div>
        {!! Form::submit('CREAR MANUAL', ['class' => 'btnCreate mt-4']) !!}

        {!! Form::close() !!}

    </div>
@stop
