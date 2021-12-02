@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Empleado</h3>
    </div>
    <div class="card-body">
        {!! Form::model($employee, ['route' => ['admin.employee.update', $employee], 'method' => 'put']) !!}
        <div class="row">
            <div class="col">
                {!! Form::label('nombre', 'Nombre del Empleado') !!}
                {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre ']) !!}
                @error('nombre')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                {!! Form::label('paterno', 'Apellido Paterno') !!}
                {!! Form::text('paterno', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el/los nombres ']) !!}
                @error('paterno')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-6">
                {!! Form::label('materno', 'Apellido Materno') !!}
                {!! Form::text('materno', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el/los nombres ']) !!}
                @error('materno')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-6">
                {!! Form::label('fecha_cumple', 'Fecha de Cumpleaños') !!}
                {!! Form::date('fecha_cumple', null, ['class' => 'form-control']) !!}
                @error('fecha_cumple')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
            <div class="col-6">
                {!! Form::label('fecha_ingreso', 'Fecha de Ingreso') !!}
                {!! Form::date('fecha_ingreso', null, ['class' => 'form-control']) !!}
                @error('fecha_ingreso')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-4">
                {!! Form::label('status', 'Status') !!}
                {!! Form::select('status', ['1' => 'Activo', '0' => 'No Activo'], null, ['class' => 'form-control']) !!}
                @error('status')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
            <div class="col-4">
                {!! Form::label('department', 'Departamento') !!}
                {!! Form::select('department', $departments, null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-4">
                {!! Form::label('position', 'Puesto') !!}
                {!! Form::select('position', $positions, null, ['class' => 'form-control']) !!}
            </div>

        </div>

        <div class="row">
            <div class="col mt-4">
                <div class="col mt-4">
                    <h5>Empresas a las que pertenece</h5>                    
                    @foreach ($companies as $company)
                    <div>
                        <label>
                            {!! Form::checkbox('companies[]', $company->id, null, ['class' => 'mr-4']) !!}
                            {{ $company->name_company }}
                        </label>
                    </div>
                @endforeach
                {!! Form::submit('ACTUALIZAR EMPLEADO', ['class' => 'btnCreate mt-4']) !!}

                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@stop
