@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar día</h3>
    </div>
    <div class="card-body">
        {!! Form::model($event, ['route' => ['admin.events.update', $event], 'method' => 'put']) !!}
        
            <div class="row ">
                <div class="col">
                    {!! Form::label('title', 'Título') !!}
                    {!! Form::text('title', $event->title, ['class' => 'form-control', 'placeholder' => 'Título del evento','maxlength' =>'200']) !!}
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
                    {!! Form::label('start', 'Día del evento') !!}
                    {!! Form::date('start',$event->start,  ['class' => 'form-control', 'placeholder' => 'Selecciona día del evento']) !!}
                    @error('date')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
    
                <div class="col-6">
                    {!! Form::label('time', 'Hora') !!}
                    {!! Form::time('time', $event->time , ['class' => 'form-control', 'placeholder' => 'Selecciona hora del evento']) !!}
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
                    {!! Form::label('description', 'Breve descripción del evento (opcional)') !!}
                    {!! Form::textarea('description',$event->description ,  ['class' => 'form-control', 'placeholder' => 'Escribe una breve descripción del evento']) !!}
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
