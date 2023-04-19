@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.createWorkplan', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a> 
            <h3 class="separator ms-2">Kit Legal Firmado</h3>
        </div>
                        
        <div>                
            <form 
                action="{{ route('rh.createUpPostulant', ['postulant_id' => $postulant->id]) }}"
                method="GET">
                 @csrf
                <button type="submit" class="btn btn-primary"> 
                    Alta de Colaborador
                    <i class="fa fa-arrow-right" aria-hidden="true"></i> 
                </button>
            </form>
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
                    <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
                    <p><small>Kit Legal Firmado</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle  no-selected" disabled="disabled">6</a>
                    <p><small>Alta de Colaborador</small></p>
                </div>
            </div>
        </div>
    </div>

    <br>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            <br>
        </div>
    @endif

    <div class="alert alert-light" role="alert">
        Sube tus documentos firmados en esta sección, estos pueden ser modificados una vez que el candidato sea dado de alta.
        <br>
        <b>Nota:</b> Si deseas subir un documento en especifico o conjunto de documentos, puedes subirlos y guardarlos, no afectara al resto de documentos subidos.
    </div>

    {!! Form::open(['route' => 'rh.storePostulantKit', 'enctype' => 'multipart/form-data']) !!}
    <h5>Kit de contratación</h5>
    <div class="container">
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3 form-group">

            <div class="col-sm">
                {!! Form::label('contact', 'Contrato') !!}
                {!! Form::file('contact',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('contact')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>

            <div class="col-sm">
                {!! Form::label('confidentiality', 'Convenio de confidencialidad' ) !!}
                {!! Form::file('confidentiality',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
                @error('confidentiality')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div>
        </div>
    </div>

    <br>
    <h5>Documentos personales</h5>
    <div class="container">
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3 form-group">
            {!! Form::text('postulant_id', $postulant->id,['class' => 'form-control', 'hidden']) !!}

            <div class="col-sm">
                {!! Form::label('cv', 'CV') !!}
                {!! Form::file('cv',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('birth_certificate', 'Acta de nacimiento') !!}
                {!! Form::file('birth_certificate',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('curp', 'CURP') !!}
                {!! Form::file('curp',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('ine', 'INE') !!}
                {!! Form::file('ine',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('nss', 'NSS') !!}
                {!! Form::file('nss',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('domicile', 'Domicilio') !!}
                {!! Form::file('domicile',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('study_centificate', 'Comprobante de estudios') !!}
                {!! Form::file('study_centificate',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('medic_centificate', 'Comprobante médico') !!}
                {!! Form::file('medic_centificate',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('bank_account', 'Cuenta de banco') !!}
                {!! Form::file('bank_account',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>

            <div class="col-sm">
                {!! Form::label('fiscal_centificate', 'Constancia de situacion fiscal') !!}
                {!! Form::file('fiscal_centificate',  ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
            </div>
        </div>
    </div>

    {!! Form::submit('GUARDAR', ['class' => 'btnCreate mt-4']) !!}

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
