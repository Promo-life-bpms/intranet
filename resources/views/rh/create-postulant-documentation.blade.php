@extends('layouts.app')

@section('content')
<div class="card-header">

    <div class="d-flex justify-content-between"> 

        <div class="d-flex flex-row">
            <a href="{{ route('rh.createMorePostulant', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a>
            <h3 style="margin-left:16px;" class="separator">Kit Legal de Ingreso</h3>
        </div>

        <div>      
            <form 
                action="{{ route('rh.createWorkplan', ['postulant_id' => $postulant->id]) }}"
                method="GET">
                @csrf
                <button type="submit" class="btn btn-primary"> 
                    Plan de Trabajo
                    <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
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
                    <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                    <p><small>Kit legal de Ingreso</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">4</a>
                    <p><small>Plan de Trabajo</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">5</a>
                    <p><small>Kit Legal Firmado</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">6</a>
                    <p><small>Alta de Colaborador</small></p>
                </div>
            </div>
        </div>
    </div>

    <br>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <h5>Generación de documentos</h5>
    <br>
    <div class="row">
        
        <div class="col cont2">
            
            <h6>Altas</h6>
            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('up_personal', "up_personal",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  ALTA PERSONAL  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
            
            <br>
            <h6>Contratos</h6> 

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('determined_contract', "determined_contract",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONTRATO DETERMINADO  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            
            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('indetermined_contract', "indetermined_contract",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONTRATO INDETERMINADO  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
           
            <br>
            <h6>Convenios</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('confidentiality_agreement', "confidentiality_agreement",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONVENIO DE CONFIDENCIALIDAD </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('no_compete_agreement', "no_compete_agreement",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONVENIO DE NO COMPETENCIA </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            <br>
            <h6>Constrancias</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('work_condition_update', "work_condition_update",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONSTANCIA DE ACTUALIZACIÓN DE CONDICIONES DE TRABAJO </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            <br> 
            <h6>Cartas</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('letter_for_bank', "letter_for_bank",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2"> CARTA PARA BANCO </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
        </div>
        
    </div>

    <br><br>

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

    </style>
    
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function wrongAlert() {
            Swal.fire('No disponible hasta generar documentos del "Kit Legal de Ingreso"');
        }
        
    </script>
@stop