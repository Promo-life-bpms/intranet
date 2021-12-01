@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Actualizar Puesto</h3>
    </div>
    <div class="card-body">
        {!! Form::model($position, ['route' => ['admin.position.update', $position], 'method' => 'put']) !!}
        <div class="row">
            <div class="col">
                {!! Form::label('name', 'Nombre puesto') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del puesto']) !!}
            </div>
            <div class="col">
                {!! Form::label('department', 'Nombre del puesto') !!}
                <select name="department" class="form-control">
                    <option value="" disabled>Seleccione..</option>
                    @foreach ($departments as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $position->department_id ? 'selected' : '' }}>
                            {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {!! Form::submit('ACTUALIZAR DEPARTAMENTO', ['class' => 'btnCreate mt-4']) !!}
    </div>
    {!! Form::close() !!}
    </div>
@stop
