@extends('layouts.app')

@section('content')
<div class="card-header">

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.dropUpdateDocumentation', ['id' => $user->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a>
            <h3 class="separator">Baja de Colaborador</h3>
        </div>
                        
        <div>                
           
        </div>
    </div>
</div>
<div class="card-body">
    
    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 33%;">  
                    <a href="{{ route('rh.dropDocumentation', ['user' => $id]) }}" type="button" class="btn btn-default btn-circle no-selected" >1</a>
                    <p><small>Fecha y Motivos de Baja</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a  href="{{ route('rh.dropUpdateDocumentation', ['id' => $user->id]) }}" type="button" class="btn btn-default btn-circle no-selected" >2</a>
                    <p><small>Documentación</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a href="#" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                    <p><small>Baja de Colaborador</small></p>
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
    <div class="container">

        <h5>Fecha de baja</h5>

        {!! Form::open(['route' => 'rh.buildDownDocumentation', 'enctype' => 'multipart/form-data']) !!}
      
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
            
            <div class="col">
                {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}

                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', $user->name,['class' => 'form-control', 'readonly']) !!}
            </div>
            <div class="col">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::text('lastname', $user->lastname,['class' => 'form-control', 'readonly' ]) !!}
            </div>
            <div class="col">
                {!! Form::label('date_down', 'Fecha de baja') !!}
                {!! Form::date('date_down', isset($user->userDetails->date_down) ? $user->userDetails->date_down : null , ['class' => 'form-control' , 'readonly'  ]) !!}
            </div> 
    </div>

    <br>
    {!! Form::close() !!}

    <br>
    
    @if ($user->userDetails != null)
        @if ($user->userDetails->date_down != null)
        <div>
            <form class="form-delete"
                action="{{ route('rh.dropDeleteUser', ['user' => $user->id]) }}"
                method="POST">
                @csrf
                @method('delete')
                <button style="width: 100%; height:50px;" type="submit" class="btn btn-danger">GENERAR BAJA</button>
            </form> 
        </div>
        @else
            <div class="alert alert-light" role="alert">
                Para poder dar de baja a este colaborador, debes llenar la <b>fecha de baja</b> descrito en el <b>PASO 1</b>.
            </div>
        @endif

    @else
        <div class="alert alert-light" role="alert">
            Para poder dar de baja a este colaborador, debes llenar la <b>fecha de baja</b> descrito en el paso <b>1 - Fecha y Motivos de Baja </b>.
        </div>
    @endif
   
          
 
@stop

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
     $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El usuario será dado de baja!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
</script>
@endsection

@section('styles')

<style>
    label {
        margin-top: 10px;
    }

    .subtitle {
        margin-top: 30px;
    }

    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .cont {
        margin-left: 24px;
        margin-right: 24px;
    }

    .cont2 {
        margin-bottom: 24px;
    }

    .separator {
        margin-left: 16px;
    }

    .arrouw-back {
        color: #1A346B;
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