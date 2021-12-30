@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear Evento</h3>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'admin.events.store']) !!}
        <div class="row ">
            <div class="col">
                {!! Form::label('title', 'Titulo') !!}
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Titulo del evento']) !!}
                @error('title')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                {!! Form::label('start', 'Dia del evento') !!}
                {!! Form::date('start', null, ['class' => 'form-control', 'placeholder' => 'Selecciona dia del evento']) !!}
                @error('start')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-6">
                {!! Form::label('time', 'Hora') !!}
                {!! Form::time('time',  null, ['class' => 'form-control', 'placeholder' => 'Selecciona hora del evento']) !!}
                @error('time')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                {!! Form::label('description', 'Breve descripcion del evento (opcional) ') !!}
                {!! Form::textarea('description',  null, ['class' => 'form-control', 'placeholder' => 'Selecciona hora del evento', 'maxlength' =>'200']) !!}
                @error('time')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>
            
            {!! Form::submit('CREAR EVENTO', ['class' => 'btnCreate mt-4']) !!}
       
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    
@stop
