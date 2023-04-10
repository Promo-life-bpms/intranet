@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Solicitud de Equipo</h1>
    
            <div class="row">
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
                        {!! Form::label('serial_number', 'Número de serie') !!}
                        {!! Form::text('serial_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el número de serie']) !!}
                        @error('serial_number')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('type', 'Tipo') !!}
                        {!! Form::text('type', null, ['class' => 'form-control', 'placeholder' => 'Seleccione el tipo']) !!}
                        @error('type')
                            <br>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('name', 'Nombre') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese nombre']) !!}
                        @error('name')
                            <br>
                        @enderror
                    </div>
                </div>
            
                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('brand', 'Marca') !!}
                        {!! Form::text('brand', null, ['class' => 'form-control', 'placeholder' => 'Seleccione la marca']) !!}
                        @error('brand')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('processor', 'Procesador') !!}
                        {!! Form::text('processor', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el procesador']) !!}
                        @error('processor')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('ram', 'Ram') !!}
                        {!! Form::text('ram', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la RAM']) !!}
                        @error('ram')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('storage', 'Almacenamiento') !!}
                        {!! Form::text('storage', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el almacenamiento']) !!}
                        @error('storage')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('device_status_id	', 'Dispositivo status id') !!}
                        {!! Form::text('device_status_id	', null, ['class' => 'form-control', 'placeholder' => 'Ingrese id status dispositivo']) !!}
                        @error('device_status_id')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('user_id', 'ID de usuario') !!}
                        {!! Form::text('user_id', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el id de usuario']) !!}
                        @error('user_id')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('category', 'Categoria') !!}
                        {!! Form::text('category', null, ['class' => 'form-control', 'placeholder' => 'Seleccione una categoria']) !!}
                        @error('category')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('state', 'Estado del material o equipo') !!}
                        {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el estado del material o equipo']) !!}
                        @error('state')
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

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('departament', 'Departamento del solicitante') !!}
                        {!! Form::text('departament', null, ['class' => 'form-control', 'placeholder' => 'Ingrese su departamento']) !!}
                        @error('departament')
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        {!! Form::label('observations', 'Observaciones') !!}
                        {!! Form::text('observations', null, ['class' => 'form-control', 'placeholder' => 'Ingrese alguna observación']) !!}
                        @error('observations')
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

