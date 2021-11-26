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

    <div class="card">
        <div class="card-body">
          {!! Form::open(['route'=>'admin.user.store']) !!}
          <div class="form-group">
            {!! Form::label('name', 'Nombre', ) !!}
            {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el nombre de usuario']) !!}
            @error('name') 
            <small> <font color="red"> *Este campo es requerido* </font></small>
            <br>
          @enderror
            {!! Form::label('name', 'Correo', ) !!}
            {!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'Ingrese el correo de acceso']) !!}
            @error('email') 
            <small> <font color="red"> *Este campo es requerido* </font></small>
            <br>
          @enderror

          <p class="mt-4">Roles</p>
          @foreach ($roles as $role)
              <div>
                <label>
                  {!! Form::checkbox('roles[]', $role->id, null, ['class'=>'mr-4']) !!}
                  {{$role->name}}
                </label>
              </div>
          @endforeach
        
            {!! Form::submit('CREAR USUARIO', ['class'=>'btnCreate mt-4']) !!}
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