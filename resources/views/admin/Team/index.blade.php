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
                                            {!! Form::label('type_of_user', 'Tipo de usuario: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('type_of_user', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de usuario']) !!}
                                            @error('type_of_user')
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
                                            {!! Form::label('area', 'Área: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('area', null, ['class' => 'form-control', 'placeholder' => 'Ingrese área']) !!}
                                            @error('area')
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
                                            {!! Form::label('extension', 'Extensión: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('extension', null, ['class' => 'form-control', 'placeholder' => 'Ingrese extensión']) !!}
                                            @error('extension')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('immediate_boss', 'Jefe inmediato: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('immediate_boss', null, ['class' => 'form-control', 'placeholder' => 'Ingrese jefe inmediato']) !!}
                                            @error('immediate_boss')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('company', 'Empresa: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::select('company', ['Promo Life' => 'Promo Life', 'BH Trade Market' => 'BH Trade Market', 'Promo Zale'=> 'Promo Zale', 'Trade Market 57'=> 'Trade Market 57', 'Unipromtex'=> 'Unipromtex'], 'postulante', ['class' => 'form-control','placeholder' => 'Seleccione empresa']) !!}
                                            @error('company')
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
                                            {!! Form::label('computer_type', 'Tipo de computadora: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('computer_type', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de computadora']) !!}
                                            @error('computer_type')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('cell_phone', 'Celular: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('cell_phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese celular']) !!}
                                            @error('cell_phone')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('number', '#: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese número']) !!}
                                            @error('number')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('extension_number', 'No. de extensión: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('extension_number', null, ['class' => 'form-control', 'placeholder' => 'Ingrese No. de extensión']) !!}
                                            @error('extension_number')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('equipment_to_use', 'Equipo a utilizar: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('equipment_to_use', null, ['class' => 'form-control', 'placeholder' => 'Ingrese equipo a utilizar']) !!}
                                            @error('equipment_to_use')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('accessories', 'Accesorios: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('accessories', null, ['class' => 'form-control', 'placeholder' => 'Ingrese accesorio']) !!}
                                            @error('accessories')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('previous_user', 'En caso de ser reasignación de equipo indique el usuario anterior: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('previous_user', null, ['class' => 'form-control', 'placeholder' => 'Ingrese usuario anterior']) !!}
                                            @error('previous_user')
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
                                            {!! Form::label('email', 'Correo: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Ingrese correo']) !!}
                                            @error('email')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('signature_or_telephone_contact_numer', 'Firma: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('signature_or_telephone_contact_numer', null, ['class' => 'form-control', 'placeholder' => 'Ingrese firma']) !!}
                                            @error('signature_or_telephone_contact_numer')
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

                                    <div id="contenedor-campos-correo-firma">
                                        <button type="button" id="agregar-campo-de-correo-firma" class="btn btn-primary">
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
                                            {!! Form::label('distribution_and_forwarding', 'Distribución y Reenvíos: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('distribution_and_forwarding', null, ['class' => 'form-control', 'placeholder' => 'Ingrese distribución y reenvíos']) !!}
                                            @error('distribution_and_forwarding')
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
                                            {!! Form::checkbox('office', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('office', 'Office: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('office')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('acrobat_pdf', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('acrobat_pdf', 'Acrobat PDF: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('acrobat_pdf')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('photoshop', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('photoshop', 'PhotoShop: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('photoshop')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('premier', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('premier', 'Premier: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('premier')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('audition', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('audition', 'Audition: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('audition')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('solid_works', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('solid_works', 'Solid Works: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('solid_works')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::checkbox('autocad', null, ['class' => 'form-check-input']) !!}
                                            {!! Form::label('autocad', 'Autocad: ', ['class' => 'form-check-label', 'style' => 'font-size: 11px;']) !!}
                                            @error('autocad')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input type="checkbox" id="checkbox-odoo" name="odoo_checkbox">
                                            <label class="form-check-label" for="odoo_checkbox" style="font-size: 11px;">ODOO:</label>
                                            @error('')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="font-size: 11px;">Usuario(s) de ODOO:</label>
                                                <input type="text" class="form-control" placeholder="Ingrese usuarios de odoo" disabled id="usuarios-odoo" name="odoo_users">
                                                <small>
                                                    {{-- <font color="red"> *Este campo es requerido* </font> --}}
                                                </small> 
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label style="font-size: 11px;">Perfil de Trabajo en ODOO:</label>
                                                <input type="text" class="form-control" placeholder="Ingrese perfil de trabajo en odoo" disabled id="perfil-odoo" name="work_profile_in_odoo">
                                                <small>
                                                    {{-- <font color="red"> *Este campo es requerido* </font> --}}
                                                </small> 
                                            </div>
                                        </div>
                                    </div>

                                    <div id="contenedor-de-campos-usuarios-perfil">
                                        <button type="button" id="agregar-campo-usuarios-perfil" class="btn btn-primary" disabled>
                                            {{-- <i class="fa fa-envelope" aria-hidden="true"></i> --}}
                                            {{-- <i class="fa fa-envelope-o" aria-hidden="true"></i> --}}
                                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                                            Agregar
                                        </button>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('others', 'Otros: ' , ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::textarea('others', null, ['class' => 'form-control', 'style' => 'width:300px; height:100px;', 'placeholder' => 'Ingrese otros']) !!}
                                            @error('others')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h2>
                            
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('access_to_server_shared_folder', 'Requiere Acceso a Carpeta Compartida del Servidor: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('access_to_server_shared_folder', null, ['class' => 'form-control', 'placeholder' => 'Ingrese acceso a carpeta compartida']) !!}
                                            @error('access_to_server_shared_folder')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('folder_path', 'Ruta de la Carpeta: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('folder_path', null, ['class' => 'form-control', 'placeholder' => 'Ingrese ruta de la carpeta']) !!}
                                            @error('folder_path')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('type_of_access', 'Tipo de Acceso: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::text('type_of_access', null, ['class' => 'form-control', 'placeholder' => 'Ingrese tipo de acceso']) !!}
                                            @error('type_of_access')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small> 
                                            @enderror
                                        </div>
                                    </div>
                            </div>

                        <p style="margin-bottom:20px;"></p> 

                            <h2 style="font-size: 18px;">Observaciones</h2>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {!! Form::label('observations', 'Observaciones: ', ['style' => 'font-size: 11px;']) !!}
                                            {!! Form::textarea('observations', null, ['class' => 'form-control', 'style' => 'width:300px; height:100px;', 'placeholder' => 'Ingrese observaciones']) !!}
                                            @error('observations')
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
                var contador = 1;
                var maxCampos = 5;
                $('#agregar-campo-de-correo-firma').click(function () {
                    if(contador<=maxCampos){
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
                                    '<input type="text" name="email'+contador+'" class="form-control" placeholder="Ingrese correo">' +
                                '</div>' +
                            '</div>' +
                            
                            '<div class="col-md-4">' +
                                '<div class="form-group">' +
                                    '<label for="firma[]">Firma:</label>' +
                                    '<input type="text" name="signature_or_telephone_contact_numer'+contador+'" class="form-control" placeholder="Ingrese firma">' +
                                '</div>';
                            '</div>' +
                        '</div>' +
                    $('#contenedor-campos-correo-firma').prepend(nuevoCampo);
                    contador++;
        
                    $('input[name="email[]"]').last().attr('id', 'email' + contador);
                    $('input[name="signature_or_telephone_contact_numer[]"]').last().attr('id', 'signature_or_telephone_contact_numer' + contador);

                } else {
                        alert('Se ha alcanzado el límite de campos permitidos.');
                    }
                });
            });
        </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"> </script>
        <script>
            $(document).ready(function () {
                var contador = 1;
                var limiteCampos = 5;
                $('#agregar-campo-usuarios-perfil').click(function () {
                    if (contador <= limiteCampos) {
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
                        '<label for="Usuario(s) de ODOO[]">Usuario(s) de ODOO:</label>' +
                        '<input type="text" name="odoo_users' + contador + '" class="form-control" placeholder="Ingrese usuarios de odoo">' +
                        '</div>' +
                        '</div>' +
            
                        '<div class="col-md-4">' +
                        '<div class="form-group">' +
                        '<label for="Perfil de Trabajo de ODOO[]">Perfil de Trabajo de ODOO:</label>' +
                        '<input type="text" name="work_profile_in_odoo' + contador + '" class="form-control" placeholder="Ingrese perfil de trabajo en odoo">' +
                        '</div>' +
                        '</div>' +
                        '</div>';
            
                    $('#contenedor-de-campos-usuarios-perfil').prepend(nuevoCampo);
                    contador++;
            
                    $('input[name="odoo_users' + contador + '"]').attr('id', 'odoo_users' + contador);
                    $('input[name="work_profile_in_odoo' + contador + '"]').attr('id', 'work_profile_in_odoo' + contador);
                    
                    } else {
                        alert('Se ha alcanzado el límite de campos permitidos.');
                    }
                });
            
                $('#checkbox-odoo').change(function () {
                    var campos = $('input[name^="odoo_users"], input[name^="work_profile_in_odoo"]');
                    var boton = $('#agregar-campo-usuarios-perfil');
            
                    if ($(this).is(':checked')) {
                        campos.prop('disabled', false);
                        boton.prop('disabled', false);
                    } else {
                        campos.prop('disabled', true);
                        boton.prop('disabled', true);
                    }
                });
            });
        </script>
            

        <script>
            document.getElementById('checkbox-odoo').addEventListener('change', function() {
                var usersField = document.getElementById('usuarios-odoo');
                var profileField = document.getElementById('perfil-odoo');
                
                if (this.checked) {
                    usersField.removeAttribute('disabled');
                    profileField.removeAttribute('disabled');
                } else {
                    usersField.setAttribute('disabled', 'disabled');
                    profileField.setAttribute('disabled', 'disabled');
                }
            });
        </script>
@endsection 