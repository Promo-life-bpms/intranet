@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Asignar Vacaciones</h3>
    </div>

    <div class="card-body">
        {!! Form::model($vacation, ['route' => ['admin.vacations.update', $vacation], 'method' => 'put']) !!}
        <div class="row" style="display:hidden;">

            <div class="col">
                <div class="mb-2 form-group" id="name">
                    {!! Form::hidden('users_id',$vacation->user->id, null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario al que pertenece']) !!}
                    {!! Form::label('users_name',$vacation->user->name .' '. $vacation->user->lastname, null, ['class' => 'form-control']) !!}
                    @error('users_id')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>

        </div>
        
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('days_availables', 'Dias de vacaciones') !!}
                    {!! Form::number('days_availables', null, ['class' => 'form-control', 'placeholder' => 'Ingresa los dias de vacaciones']) !!}
                    @error('days_availables')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('expiration', 'Fecha de vencimiento') !!}
                    {!! Form::date('expiration',null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                    @error('expiration')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
           
        </div>
        {!! Form::submit('ACTUALIZAR VACACIONES', ['class' => 'btnCreate mt-4']) !!}
    </div>
    {!! Form::close() !!}

@stop


@section('styles')
    <style>
        #name>*{
            font-size: 20px;
            
        }
    </style>
@stop



@section('scripts')
    
@stop
