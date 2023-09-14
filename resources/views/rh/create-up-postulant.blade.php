@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.createSignedKit', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a> 
            <h3 class="separator ms-2">Alta de Colaborador</h3>
        </div>
                        
        <div>                
            
        </div>
    </div>
</div>
<div class="card-body">

<div class="container" >
    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step col-xs-3" style="width: 16.6%;">  
                <a href="#step-1" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">1</a>
                <p><small>Alta de Candidato</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                <a href="#step-2" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">2</a>
                <p><small>Recepción de Documentos</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                <a href="#step-3" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
                <p><small>Kit legal de Ingreso</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                <a href="#step-4" type="button" class="btn btn-default btn-circle  no-selected" disabled="disabled">4</a>
                <p><small>Plan de Trabajo</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">5</a>
                <p><small>Kit Legal Firmado</small></p>
            </div>
            <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">6</a>
                <p><small>Alta de Colaborador</small></p>
            </div>
        </div>
    </div>
</div>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            <br>
        </div>
    @endif
    <br>
    <div class="alert alert-light" role="alert">
        Estás a un paso de dar de alta al colaborador en el sistema, confirma la información y rellena los campos faltantes (*).
    </div>
    <br>
    <h5>Información de ingreso</h5>
    <br>
    {!! Form::open(['route' => 'rh.storeUpPostulant', 'enctype' => 'multipart/form-data']) !!}

    <div class="row form-group">

            {!! Form::text('postulant_id', $postulant->id, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario', 'hidden']) !!}

            <div class="col-sm">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', $postulant->name, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                @error('name')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
                
            <div class="col-sm">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::text('lastname', $postulant->lastname, ['class' => 'form-control', 'placeholder' => 'Ingrese los apellidos de usuario']) !!}
                @error('lastname')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm ">
                {!! Form::label('date_admission', 'Fecha de ingreso') !!}
                {!! Form::date('date_admission', $postulant->date_admission, ['class' => 'form-control', 'placeholder' => 'Ingrese fecha de ingreso']) !!}
                @error('date_admission')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>  
        </div>

       
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('email', 'Correo electrónico') !!}
                {!! Form::text('email', $postulant->email, ['class' => 'form-control','placeholder' => 'Ingrese el correo electrónico']) !!}
                @error('email')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                {!! Form::date('birthdate', $postulant->birthdate, ['class' => 'form-control','placeholder' => 'Ingrese la fecha de nacimiento']) !!}
                @error('birthdate')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 
            <div class="col-sm"> </div> 
            
        </div>
        
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('company_id', 'Empresa') !!}
                {!! Form::select('company_id',$companies , $postulant->company_id, ['class' => 'form-control','placeholder' => 'Seleccione la empresa...']) !!}
                @error('company_id')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('department_id', 'Departamento') !!}
                {!! Form::select('department_id', $departments, $postulant->department_id, ['class' => 'form-control','placeholder' => 'Seleccione el departamento...']) !!}
                @error('department_id')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('jefe_directo_id', 'Jefe Directo', ['class' => 'required']) !!}
                {!! Form::select('jefe_directo_id', $users, null, ['class' => 'form-control','placeholder' => 'Seleccione el jefe directo...']) !!}
                @error('jefe_directo_id')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>   
        </div>
        
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('vacant', 'Vacante') !!}
                {!! Form::text('vacant', $postulant->vacant, ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('vacant')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
            <div class="col-sm"></div>
            <div class="col-sm"></div>

        </div>
        
        @if ($postulant->status != 'colaborador')
            {!! Form::submit('CREAR ALTA DE COLABORADOR', ['class' => 'btnCreate mt-4']) !!}
        @endif
  
    </div>

    {!! Form::close() !!}

    
    @if ($postulant->status == 'colaborador')
        <br>
        <div>
            <form 
                action="{{ route('admin.users.index') }}"
                method="GET">
                @csrf
                <button  style="width:100%; height:50px;" type="submit" class="btn btn-success"> 
                    VER LISTA DE USUARIOS
                </button>
            </form>
        </div>
    @endif
    
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

    .stepwizard-step p {
        margin-top: 0px;
        color:#666;
    }
    .stepwizard-row {
        display: table-row;
    }
    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
    }
    .btn-default{
        background-color: #0084C3;
    }

    .stepwizard-row:before {
        top: 14px;
        bottom: 0;
        position: absolute;
        content:" ";
        width: 100%;
        height: 1px;
        background-color: #ccc;
        z-index: 0;
    }
    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
    }
    .btn-circle {
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
        color: #fff;
    }

    .no-selected{
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 6px 0;
        font-size: 12px;
        line-height: 1.428571429;
        border-radius: 15px;
        color: #000;
        background-color: #fff;
        border-color: #0084C3;
    }
    .required:after {
        content:" *";
        color: red;
    }
</style>
@endsection


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="department_id"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/dropdownlist/getPosition/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
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
    </script>
    {{-- <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="position"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/user/getManager/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery('select[name="jefe_directo_id"]').empty();
                            jQuery.each(data, function(key, value) {
                                $('select[name="jefe_directo_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="employee_id"]').empty();
                }
            });
        });
    </script> --}}
@stop