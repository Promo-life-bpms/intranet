@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <h1 style="font-size:20px">Solicitud de Servicios de Sistemas y Comunicaciones</h1>
            
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success" role="success">
            {{session('success')}}
        </div>   
        @endif
    

    <form action="{{route('team.createTeamRequest')}}" method="POST">

            {!! Form::open(['route' => 'team.request', 'enctype' => 'multipart/form-data']) !!}
                <h2 style="font-size: 20px;">Datos Generales del Personal de Nuevo Ingreso</h2>
                 @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Tipo de usuario: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de usuario']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Nombre: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Fecha Requerida: ') !!}
                                <input type="date" id="fecha" name="fecha" class="form-control">
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Área: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el área']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Departamento: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el departamento']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Puesto: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el puesto']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Extensión: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la extensión']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Jefe inmediato: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese a jefe inmediato']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Empresa: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la empresa']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Gerente de área: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese a gerente de área']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                <h2 style="font-size: 20px;">Asignación de Equipo de Cómputo y Telefonía</h2>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Tipo de computadora: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de computadora']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Celular: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el celular']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', '#: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el número']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'No. de extensión: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el No. de extensión']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Equipo a utilizar: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el equipo a utilizar']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Accesorios: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el accesorio']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'En caso de ser reasignación de equipo indique el usuario anterior: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario anterior']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>
                
                <h2 style="font-size: 20px;">Cuenta(s) de Correo(s) Requerida(s)</h2>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Correo: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('', 'Firma: ') !!}
                                {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo']) !!}
                                @error('')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small> 
                                @enderror
                            </div>
                        </div>
                    </div>
    </div>
        {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4']) !!}
</div>
        {!! Form::close()!!}
    </form>
@endsection


