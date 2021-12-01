@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear Puesto</h3>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'admin.position.store']) !!}
        <div class="row">
            <div class="col">
                {!! Form::label('name', 'Nombre del puesto') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del puesto']) !!}
            </div>
            <div class="col">
                {!! Form::label('department', 'Nombre del puesto') !!}
                <select name="department" class="form-control">
                    <option value="" disabled selected>Seleccione..</option>
                    @foreach ($departments as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {!! Form::submit('CREAR PUESTO', ['class' => 'btnCreate mt-4']) !!}
    </div>
    {!! Form::close() !!}
    </div>
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">

@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
@stop
