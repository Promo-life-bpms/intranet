@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Crear solicitud</h3>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'request.store']) !!}
        <div class="row">
            <div class="col">
                {!! Form::label('nombre_solicitud', 'Titulo Solicitud') !!}
                {!! Form::text('nombre_solicitud', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
        </div>

        <div class="row">
            <div class="col">
                {!! Form::label('fecha_solicitud', 'Fecha de Solicitud') !!}
                {!! Form::date('fecha_solicitud', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="col-4">
                {!! Form::label('tipo_soli', 'Fecha de Solicitud') !!}
                {!! Form::date('tipo_soli', null, ['class' => 'form-control']) !!}
            </div>

            <div class="col-4">
                {!! Form::label('especificacion_soli ', 'Status') !!}
                {!! Form::select('especificacion_soli ', ['1' => 'Salir durante jornada', '2' => 'Ausentarse'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            
            <div class="col-4">
                {!! Form::label('especificacion_soli ', 'Status') !!}
                {!! Form::select('especificacion_soli ', ['1' => 'Descontar tiempo/dia', '2' => 'A cuenta de vacaciones'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="col">
                {!! Form::label('motivo_solicitud ', 'Motivo') !!}
                {!! Form::textarea('motivo_solicitud ', null,  ['class' => 'form-control']) !!}
            </div>
        </div>
        {!! Form::submit('CREAR EMPLEADO', ['class'=>'btnCreate mt-4']) !!}       
    </div>
    {!! Form::close() !!}
</div>
    
@stop
