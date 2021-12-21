@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Acceso</h3>
    </div>

    <div class="card-body">
        {!! Form::model($acc, ['route' => ['access.update', $acc], 'method' => 'put','enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('title', 'Título') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el título']) !!}
                    @error('title')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('link', 'URL del sitio') !!}
                    {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el link del sitio']) !!}
                    @error('link')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2 form-group">
                    {!! Form::label('user', 'Guardar usuario (opcional)') !!}
                    {!! Form::text('user', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario del sitio']) !!}
                    @error('user')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2 form-group">
                    {!! Form::label('password', 'Guardar contraseña (opcional)') !!}
                    {!! Form::text('password', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario del sitio']) !!}
                    @error('password')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="mb-2 form-group">
                    {!! Form::label('image', 'Imagen representativa') !!}
                    {!! Form::file('image',  ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>
        {!! Form::submit('GUARDAR', ['class' => 'btnCreate mt-4']) !!}
    </div>
    {!! Form::close() !!}

@stop

@section('scripts')
    
@stop
