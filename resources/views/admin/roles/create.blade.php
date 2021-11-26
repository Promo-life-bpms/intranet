@extends('layouts.app')
@section('title')
    <h3>Crear Rol</h3>
@endsection

@section('content')
    <div class="card-body">
      {!! Form::open(['route'=>'admin.roles.store']) !!}
      <div class="form-group">
        {!! Form::label('name', 'Nombre Rol', ) !!}
        {!! Form::text('name', null, ['class'=>'form-control', 'placehorlder'=>'Ingrese el nombre del rol']) !!}
        @error('name')
        <small> <font color="red"> *Este campo es requerido* </font></small>
        <br>
      @enderror


      <p class="mt-4">Lista de permisos</p>
      @foreach ($permissions as $permission)
          <div>
            <label>
              {!! Form::checkbox('permissions[]', $permission->id, null, ['class'=>'mr-4']) !!}
              {{$permission->description}}
            </label>
          </div>
      @endforeach

        {!! Form::submit('CREAR ROL', ['class'=>'btnCreate mt-4']) !!}
      </div>

      {!! Form::close() !!}
    </div>
@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop
