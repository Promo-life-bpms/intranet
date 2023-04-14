@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.createPostulantDocumentation', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a> 
            <h3 class="separator ms-2">Alta de Colaborador</h3>
        </div>
                        
        <div>                
            <form 
                action="{{ route('rh.createWorkplan', ['postulant_id' => $postulant->id]) }}"
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

<br>
    <h5>Sube aqui tus documentos firmados</h5>
    
    
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

</style>
@endsection
