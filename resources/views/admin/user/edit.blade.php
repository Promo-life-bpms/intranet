@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Usuario</h3>
    </div>
    <div class="card-body">
        {!! Form::model($user, ['route' => ['admin.user.update', $user], 'method' => 'put']) !!}
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            @error('name')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror
            {!! Form::label('name', 'Correo') !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo de acceso']) !!}
            @error('email')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror

            <p class="mt-4">Roles</p>
            @foreach ($roles as $role)
                <div>
                    <label>
                        {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'mr-4']) !!}
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach

            {!! Form::submit('ACTUALIZAR USUARIO', ['class' => 'btnCreate mt-4']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@stop
