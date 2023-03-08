@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex flex-row" >
            <a  href="{{ route('rh.postulants') }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
            </a>
            <h3 style="margin-left:16px;" class="separator">Informacion de alta</h3> 
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        {!! Form::open(['route' => 'rh.storePostulant', 'enctype' => 'multipart/form-data']) !!}
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
                    {!! Form::select('status', ['postulante' => 'Postulante', 'candidato' => 'Candidato', 'empleado' => 'Empleado'], 'postulante', ['class' => 'form-control','placeholder' => 'Selecciona status de postulante']) !!}
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
                {!! Form::label('mail', 'Correo') !!}
                {!! Form::text('mail', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('phone', 'Telefono') !!}
                {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono celular']) !!}
            </div>
              
            <div class="col-sm">
                {!! Form::label('cv', 'CV o Solicitud Elaborada (opcional)') !!}
                {!! Form::file('cv', ['class' => 'form-control']) !!}
            </div>  
        </div>
        <div class="row form-group">
            <div class="col-sm">
                {!! Form::label('company_id', 'Empresa de interes') !!}
                {!! Form::select('company_id', $companies, null, ['class' => 'form-control']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('department_id', 'Departamento de interes') !!}
                {!! Form::select('department_id', $departments, null, ['class' => 'form-control']) !!}
            </div>

            <div class="col-sm ">
                {!! Form::label('department_id', 'Fecha de entrevista (opcional)') !!}
                <input type="datetime-local" id="meeting-time"
                name="interview_date" class="form-control">
            </div>
        </div>

        {!! Form::submit('GUARDAR', ['class' => 'btnCreate mt-4']) !!}
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
