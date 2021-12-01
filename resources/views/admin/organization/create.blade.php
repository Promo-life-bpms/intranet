@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear Empresa</h3>
    </div>
    <div class="card-body">
        {!! Form::open(['route' => 'admin.organization.store']) !!}
            <div class="row">
                <div class="col">
                    {!! Form::label('name_company', 'Nombre empresa') !!}
                    {!! Form::text('name_company', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la empresa']) !!}
                </div>
            </div>

            <div class="row mt-4">
                <div class="col">
                    {!! Form::label('description_company', 'Direccion de la empresa') !!}
                    {!! Form::text('description_company', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la direccion de la empresa']) !!}
                </div>

            {!! Form::submit('CREAR EMPRESA', ['class' => 'btnCreate mt-4']) !!}
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
