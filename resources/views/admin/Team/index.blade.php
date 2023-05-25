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
                <h2 style="font-size: 18px;">Datos Generales del Personal de Nuevo Ingreso</h2>
                 @csrf
                    <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Tipo de usuario: ', ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Nombre: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Fecha requerida: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Área: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Departamento: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Puesto: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Extensión: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Jefe inmediato: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Empresa: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la empresa']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>
                                

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Asignación de Equipo de Cómputo y Telefonía</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Tipo de computadora: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Celular: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', '#: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'No. de extensión: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Equipo a utilizar: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Accesorios: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'En caso de ser reasignación de equipo indique el usuario anterior: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el usuario anterior']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>
                        
                        <p style="margin-bottom:20px;"></p>

                                    <div class="d-flex justify-content-around">
                                        <div class="col"> 
                                            <h2 style="font-size: 18px;">Cuenta(s) de Correo(s) Requerida(s)</h2>
                                        </div>
                                        
                                        <div class="col"> 
                                            <h2 style="font-size: 18px;">Firma: Número(s) de Contacto Telefónico</h2>
                                        </div>

                                        <div class="col"> 
                                            
                                        </div>
                                    </div>
                            
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Firma: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Distribución y Reenvíos: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la distribución y reenvíos']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Software Requerido</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Office: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese office']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Acrobat PDF: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese acrobat PDF']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Photo Shop: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese photo shop']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Premier: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese premier']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Audition: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese audition']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Solid Works: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese solid works']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Autocad: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese autocad']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'ODOO: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el ODOO']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Promo Life: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese promo life']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'BH: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese bh']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Promo Zale: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese promo zale']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Trade Market 57: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese trade market 57']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Usuarios(s) de ODOO: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese los usuarios de odoo']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', '1.- ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', '2.- ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Perfil de Trabajo en ODOO: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el perfil de trabajo en ODOO']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', '1.- ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', '2.- ' , ['style' => 'font-size: 11px;']) !!}
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
                                            {!! Form::label('', 'Otros: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese otros']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Requiere Acceso a Carpeta Compartida del Servidor: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el acceso a carpeta compartida']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Ruta de la Carpeta: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la ruta de la carpeta']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Tipo de Acceso: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el tipo de acceso']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Observaciones</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Observaciones: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese las observaciones']) !!}
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


