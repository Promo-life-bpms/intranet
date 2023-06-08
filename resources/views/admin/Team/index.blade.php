@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Solicitud de Servicios de Sistemas y Comunicaciones</h1>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de usuario']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('jefe_directo_id', 'Nombre', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::select('jefe_directo_id', $manager, null, ['class' => 'form-control','placeholder' => 'Seleccione nombre']) !!}
                                            @error('jefe_directo_id')
                                                <small>
                                                    <font color="red"> *Este campo es requerido* </font>
                                                </small>
                                                <br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('date_admission', 'Fecha requerida' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('date_admission', null, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha']) !!}
                                            @error('date_admission')
                                                <small>
                                                    <font color="red"> *Este campo es requerido* </font>
                                                </small>
                                                <br>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Área: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese área']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('department', 'Departamento', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('department', null, ['class' => 'form-control','placeholder' => 'Ingrese departamento']) !!}
                                            @error('department')
                                                <small>
                                                    <font color="red"> *Este campo es requerido* </font>
                                                </small>
                                                <br>
                                            @enderror
                                        </div>
                                    </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Form::label('position', 'Puesto', ['style' => 'font-size: 11px;']) !!}
                                        {!! Form::text('position', null, ['class' => 'form-control','placeholder' => 'Ingrese puesto']) !!}
                                        @error('position')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Extensión: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese extensión']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese jefe inmediato']) !!}
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
                                            {!! Form::select('status', ['Promo Life' => 'Promo Life', 'BH Trade Market' => 'BH Trade Market', 'Promo Zale'=> 'Promo Zale', 'Trade Market 57'=> 'Trade Market 57', 'Unipromtex'=> 'Unipromtex'], 'postulante', ['class' => 'form-control','placeholder' => 'Seleccione empresa']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de computadora']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese celular']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese No. de extensión']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese equipo a utilizar']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese accesorio']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese usuario anterior']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div id="contenedor-campos">
                                        <button type="button" id="agregar-campo" class="btn btn-primary">
                                            {{-- <i class="fa fa-envelope" aria-hidden="true"></i> --}}
                                            {{-- <i class="fa fa-envelope-o" aria-hidden="true"></i> --}}
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                            Agregar
                                        </button>
                                    </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('', 'Distribución y Reenvíos: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese distribución y reenvíos']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese odoo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese usuarios de odoo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese perfil de trabajo en odoo']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese acceso a carpeta compartida']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese ruta de la carpeta']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de acceso']) !!}
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
                                            {!! Form::text('', null, ['class' => 'form-control', 'placeholder' => 'Ingrese observaciones']) !!}
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

        <!--<script>
            var nombres = ['David', 'Jose'];
            $('#search').autocomplete({
                source: nombres
            });
        </script>-->
    </form>
@endsection

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    {{-- <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="department"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/dropdownlist/getPosition/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {

                            jQuery('select[name="position"]').empty();
                            jQuery.each(data.positions, function(key, value) {
                                $('select[name="position"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                            jQuery('select[name="jefe_directo_id"]').empty();
                            jQuery.each(data.users, function(key, value) {
                                $('select[name="jefe_directo_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="position"]').empty();
                }
            });
        });
    </script> --}}

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('select[name="jefe_directo_id"]').on('change', function() {
            var id = jQuery(this).val();
            if (id) {
                jQuery.ajax({
                    url: '/dropdownlist/getUser/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {

                        $("#date_admission").val(data.date_admission)
                        $("#position").val(data.position)
                        $("#department").val(data.department)
                        // console.log(data);
                    }
                });
            } else {
                $('select[name="position"]').empty();
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"> </script>
        <script>
            $(document).ready(function () {
                $('#agregar-campo').click(function () {
                    var nuevoCampo = 
                        '<style>' +
                        '.form-group label {' +
                        ' font-size: 11px;' +
                        '}' +
                        '.form-control {' +
                        ' font-size: 14px;' +
                        '}' +
                        '</style>' +

                        '<div class="row">' +
                            '<div class="col-md-4">' +
                                '<div class="form-group">' +
                                    '<label for="correo[]">Correo:</label>' +
                                    '<input type="text" name="correo[]" class="form-control" placeholder="Ingrese correo">' +
                                '</div>' +
                            '</div>' +
                            
                            '<div class="col-md-4">' +
                                '<div class="form-group">' +
                                    '<label for="firma[]">Firma:</label>' +
                                    '<input type="text" name="firma[]" class="form-control" placeholder="Ingrese firma">' +
                                '</div>';
                            '</div>' +
                        '</div>' +
                    $('#contenedor-campos').prepend(nuevoCampo);
                });
            });
        </script>
@endsection 




