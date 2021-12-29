@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear Dia</h3>
    </div>
    <div class="card-body">
        {!! Form::model($event, ['route' => ['admin.events.update', $event], 'method' => 'put']) !!}
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
            <div class="row mt-4">
                <div class="col-6">
                    {!! Form::label('start', 'Dia de Inicio') !!}
                    {!! Form::date('start',  null, ['class' => 'form-control', 'placeholder' => 'Selecciona dia del evento']) !!}
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
                    @error('start')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
            
            {!! Form::submit('CREAR DIA NO LABORAL', ['class' => 'btnCreate mt-4']) !!}
        </div>
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    
@stop
