@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar comunicado</h3>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif

        {!! Form::model($communique, ['route' => ['admin.communique.update', $communique], 'method' => 'put','enctype' => 'multipart/form-data']) !!}

        <div class="row">
            <div class="col-md-7">

                <div class="mb-3 form-group">
                    {!! Form::label('title', 'Título') !!}
                    {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el título']) !!}
                    @error('title')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>


                <div class="row mt-4">
                    <div class="col-12">
                        <div class="mb-2 form-group">
                            {!! Form::label('image', 'Imagen (opcional)') !!}
                            {!! Form::file('image',  ['class' => 'form-control']) !!}
                            @error('image')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror

                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="mb-2 form-group">
                            {!! Form::label('file', 'Archivo (opcional)') !!}
                            {!! Form::file('file',  ['class' => 'form-control']) !!}

                        </div>
                    </div>
                </div>

                <div class="mb-3 mt-4 form-group">
                    {!! Form::label('description', 'Descripción') !!}
                    {!! Form::textarea('description',  null, ['class' => 'form-control', 'placeholder' => 'Ingresar descripción del comunicado']) !!}
                    @error('description')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror

                </div>

            </div>
            <div class="col-md-5">
                <div class="row mb-3">
                    <div class="form-group col-md-6 ">
                        {!! Form::label('companies', 'Enviar a empresa especifica:') !!}
                        @foreach ($companies as $company)
                            <div>
                                <label>
                                    {!! Form::checkbox('companies[]', $company->id, null, ['class' => 'mr-4']) !!}
                                    {{ $company->name_company }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group col-md-6 ">
                        {!! Form::label('departments', 'Enviar a departamento especifico:') !!}
                        @foreach ($departments as $department)
                            <div>
                                <label>
                                    {!! Form::checkbox('departments[]', $department->id, null, ['class' => 'mr-4']) !!}
                                    {{ $department->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <input type="submit" class="btnCreate" value="ACTUALIZAR COMUNICADO"></button>
        {!! Form::close() !!}
        
    </div>
@stop
