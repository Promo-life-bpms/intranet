@extends('layouts.app')

@section('dashboard')

<div class="contenedor-logo">
        <ul class="logos" style="padding-left: 10px;">
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/bhtrade.png')}}"  alt="bhtrade"></a> </li>
            <li><a  href="#"><img style="max-width: 80px;" src="{{asset('/img/promolife.png')}}"  alt="promolife"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;"src="{{asset('/img/promodreams.png')}}"  alt="promodreams"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/trademarket.png')}}"  alt="trademarket"></a> </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-8 ">
          <h3>Agregar Empleado</h3>
        </div>


        <div class="card">
            <div class="card-body">
                {!! Form::model($employee,['route'=>['admin.employee.update',$employee], 'method'=>'put']) !!}
                    <div class="row">
                        <div class="col">
                            {!! Form::label('nombre', 'Nombre del Empleado') !!}
                            {!! Form::text('nombre', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre ']) !!}
                            @error('nombre') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-6">
                            {!! Form::label('paterno', 'Apellido Paterno') !!}
                            {!! Form::text('paterno', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el/los nombres ']) !!}
                            @error('paterno') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6">
                            {!! Form::label('materno', 'Apellido Materno') !!}
                            {!! Form::text('materno', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el/los nombres ']) !!}
                            @error('materno') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-4">
                            {!! Form::label('fecha_cumple', 'Fecha de Cumpleaños') !!}
                            {!! Form::date('fecha_cumple', null, ['class'=>'form-control']) !!}
                            @error('fecha_cumple') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                        <div class="col-4">
                            {!! Form::label('fecha_ingreso', 'Fecha de Ingreso') !!}
                            {!! Form::date('fecha_ingreso', null, ['class'=>'form-control']) !!}
                            @error('fecha_ingreso') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                        <div class="col-4">
                            {!! Form::label('status', 'Status') !!}
                            {!! Form::select('status', ['1' => 'Activo', '0' => 'No Activo'], null, ['class' => 'form-control']) !!}
                            @error('status') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            {!! Form::label('id_contacto', 'Contacto') !!}
                            {!! Form::select('id_contacto',$contacts, null, ['class' => 'form-control']) !!}
                            @error('id_contacto') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                        <div class="col">
                            {!! Form::label('id_user', 'Usuario') !!}
                            {!! Form::select('id_user', $users, null, ['class' => 'form-control']) !!}
                            @error('id_user') 
                                <small> <font color="red"> *Este campo es requerido* </font></small>
                                <br>
                            @enderror
                        </div>
                        {!! Form::submit('ACTUALIZAR EMPLEADO', ['class'=>'btnCreate mt-4']) !!}
                    </div>
            
                {!! Form::close() !!}
            </div>
        </div>
      
        
@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop