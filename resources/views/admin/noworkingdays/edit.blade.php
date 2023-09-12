@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar día</h3>
    </div>
    <div class="card-body">
        {!! Form::model($noworkingday, ['route' => ['admin.noworkingdays.update', $noworkingday], 'method' => 'put']) !!}
        <div class="row ">
            <div class="form-group col-6">
                {!! Form::label('day', 'Seleccionar día') !!}
                {!! Form::date('day', null, ['class' => 'form-control', 'placeholder' => 'Selecciona día']) !!}
                @error('day')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="form-group col-6">
                {!! Form::label('companies_id', 'Empresa') !!}
                {!! Form::select('companies_id',$companies, null, ['class' => 'form-control', 'placeholder' => 'Selecciona la empresa a la que pertenece']) !!}
                @error('companies_id')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="form-group col mt-4">
                {!! Form::label('reason', 'Celebración') !!}
                {!!  Form::text('reason',null,['class' => 'form-control', 'placeholder' => 'Ingrese la descripción de la celebración']) !!}
                @error('reason')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>


            {!! Form::submit('ACTUALIZAR DÍA NO LABORAL', ['class' => 'btnCreate mt-4']) !!}
        </div>
        {!! Form::close() !!}
    </div>


@stop

@section('scripts')
    
@stop
