@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Dia</h3>
    </div>
    <div class="card-body">
        {!! Form::model($event, ['route' => ['admin.events.update', $event], 'method' => 'put']) !!}
        
            <div class="row ">
                <div class="col">
                    {!! Form::label('title', 'Titulo') !!}
                    {!! Form::text('title', $event->title, ['class' => 'form-control', 'placeholder' => 'Titulo del evento','maxlength' =>'200']) !!}
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
                    {!! Form::date('start',$event,  ['class' => 'form-control', 'placeholder' => 'Selecciona dia del evento']) !!}
                    @error('date')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
    
                <div class="col-6">
                    {!! Form::label('time', 'Hora') !!}
                    {!! Form::time('time', $event->start , ['class' => 'form-control', 'placeholder' => 'Selecciona hora del evento']) !!}
                    @error('start')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    {!! Form::label('description', 'Breve descripcion del evento (opcional)') !!}
                    {!! Form::textarea('description',$event->description ,  ['class' => 'form-control', 'placeholder' => 'Selecciona hora del evento']) !!}
                    @error('description')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
            
            {!! Form::submit('ACTUALIZAR EVENTO', ['class' => 'btnCreate mt-4']) !!}
       
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    
@stop
