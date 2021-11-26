@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Rol</h3>
    </div>
    <div class="card-body">
        {!! Form::model($role, ['route' => ['admin.roles.update', $role], 'method' => 'put']) !!}
        <div class="form-group">
            {!! Form::label('name', 'Nombre Rol') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del rol']) !!}

            @error('name')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror


            <p class="mt-4">Lista de permisos</p>
            @foreach ($permissions as $permission)
                <div>
                    <label>
                        {!! Form::checkbox('permissions[]', $permission->id, null, ['class' => 'mr-4']) !!}
                        {{ $permission->description }}
                    </label>
                </div>
            @endforeach

            {!! Form::submit('ACTUALIZAR ROL', ['class' => 'btnCreate mt-4']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@stop
