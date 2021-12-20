@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear Dia</h3>
    </div>
    <div class="card-body">
        {!! Form::model($noworkingday, ['route' => ['admin.noworkingdays.update', $noworkingday], 'method' => 'put']) !!}
        <div class="row ">
            <div class="col-6">
                {!! Form::label('day', 'Seleccionar Dia') !!}
                {!! Form::date('day', null, ['class' => 'form-control', 'placeholder' => 'Selecciona Dia']) !!}
                @error('day')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-6">
                {!! Form::label('companies_id', 'Empresa') !!}
                {!! Form::select('companies_id',$companies, null, ['class' => 'form-control', 'placeholder' => 'Selecciona la empresa a la que pertenece']) !!}
                @error('companies_id')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col mt-4">
                {!! Form::label('reason', 'Celebreacion') !!}
                {!!  Form::text('reason',null,['class' => 'form-control', 'placeholder' => 'Ingrese la descripcion de la celebracion']) !!}
                @error('reason')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>


            {!! Form::submit('ACTUALIZAR DIA NO LABORAL', ['class' => 'btnCreate mt-4']) !!}
        </div>
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    
@stop
