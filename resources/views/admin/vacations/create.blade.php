@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Asignar Vacaciones</h3>
    </div>

    <div class="card-body">
        {!! Form::open(['route' => 'admin.vacations.store']) !!}
        <div class="row">
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
            <div class="col">
                <div class="mb-2 form-group">
                    {!! Form::label('users_id', 'Usuario') !!}
                    {!! Form::select('users_id',$users, null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario al que pertenece']) !!}
                    @error('users_id')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
        </div>
        {!! Form::submit('GUARDAR', ['class' => 'btnCreate mt-4']) !!}
    </div>
    {!! Form::close() !!}

@stop

@section('scripts')
    
@stop
