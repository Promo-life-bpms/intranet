@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Solicitud de Equipo</h1>
    
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('id_user', 'ID del usuario') !!}
                        {!! Form::text('id_user', null, ['class' => 'form-control', 'placeholder' => 'Ingrese su ID']) !!}
                        @error('id_user')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('category', 'Categoria') !!}
                        {!! Form::text('category', null, ['class' => 'form-control', 'placeholder' => 'Seleccione la categoria']) !!}
                        @error('category')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('status', 'Estado') !!}
                        {!! Form::text('status', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el estado']) !!}
                        @error('status')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('id', 'ID') !!}
                        {!! Form::text('id', null, ['class' => 'form-control', 'placeholder' => 'Ingrese ID']) !!}
                        @error('id')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('description', 'Descripción') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripción']) !!}
                        @error('description')
                            <br>
                        @enderror
                    </div>
                </div>
            </div>
    </div>
    {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4']) !!}
</div>
        {!! Form::close()!!}
@endsection

