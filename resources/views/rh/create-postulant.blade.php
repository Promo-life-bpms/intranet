@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex flex-row" >
            <a  href="{{ route('rh.newUser') }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
            </a>
            <h3 style="margin-left:16px;" class="separator">Informacion de alta</h3> 
        </div>
    </div>
    <div class="card-body">

        {!! Form::open(['route' => 'admin.users.store', 'enctype' => 'multipart/form-data']) !!}
        <h6>Informacion Personal</h6>
        <p></p>
        <div class="row form-group">
                <div class="col-sm">
                    {!! Form::label('name', 'Nombre') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                
                <div class="col-sm">
                    {!! Form::label('lastname', 'Apellidos') !!}
                    {!! Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="col-sm">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', ['candidato' => 'Candidato', 'postulante' => 'Postulante', 'empleado' => 'Empleado'], null, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
        </div>

        <br>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('mail', 'Correo') !!}
                {!! Form::text('mail', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('phone', 'Telefono') !!}
                {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('company', 'Empresa') !!}
                {!! Form::select('company', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        <br> 
        <br>
        <div class="d-flex flex-row"> 
            <h6>Informacion adicional</h6>
            <div id='content'>
                <div id='icon'>
                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                </div>
            </div>
            <div id='icon-text'>
                La información adicional solo es requerida previo a la generación de la documentación de alta o cuando el status sea candidato
            </div>
        </div>
        <p></p>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('place_of_birth', 'Lugar de nacimiento') !!}
                {!! Form::text('place_of_birth', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                {!! Form::text('birthdate', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('age', 'Edad') !!}
                {!! Form::select('age', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('fathers_name', 'Nombre del padre') !!}
                {!! Form::text('fathers_name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('mothers_name', 'Nombre de la madre') !!}
                {!! Form::text('mothers_name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('civil_status', 'Estado civil') !!}
                {!! Form::select('civil_status', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('address', 'Direccion') !!}
                {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('street', 'Calle') !!}
                {!! Form::text('street', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('colony', 'Colonia') !!}
                {!! Form::select('colony', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('delegation', 'Delegacion o municipio') !!}
                {!! Form::text('delegation', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('postal_code', 'CP') !!}
                {!! Form::text('postal_code', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('imss_number', 'Numer de afiliación IMSS') !!}
                {!! Form::select('imss_number', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        <div class="row form-group">
            <div class="col-sm ">
                {!! Form::label('infonavit_credit', 'Infonavit') !!}
                {!! Form::text('infonavit_credit', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('fonacot_credit', 'Fonacot') !!}
                {!! Form::text('fonacot_credit', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
            </div>
              
            <div class="col-sm ">
                {!! Form::label('salary_sd', 'Percepción salarial') !!}
                {!! Form::select('salary_sd', $companies, null, ['class' => 'form-control']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
            </div>  
        </div>

        {!! Form::submit('GUARDAR INFORMACIÓN', ['class' => 'btnCreate mt-4']) !!}
    </div>

    {!! Form::close() !!}
   
    </div>
@stop

@section('styles')
   <style>
        .text-info{
            display: none;
        }
        .fa-info-circle{
            margin-left: 8px;
            color: #1A346B;
        }

        .fa-info-circle:hover {
            margin-left: 8px;
            color: #0084C3;
        }
      
        #icon-text {
            display: none;
            margin-left: 16px;
            color: #fff;
            background-color: #1A346B;
            padding: 0 12px 0 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        #content:hover~#icon-text{
            display: block;
        }
   </style>
@endsection
