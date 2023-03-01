@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Documentación de baja</h3>
    </div>
    <div class="card-body">
        <h5>Generar documentacion de baja</h5>

        <div class="row gx-5 ">
            <div class="row g-0 text-center">
               
            <div class="col-sm-6 col-md-3 p-4" >

            {!! Form::model($user, [
            'route' => ['admin.users.update', $user],
            'method' => 'put',
            'enctype' => 'multipart/form-data',
            ]) !!}
                {!! Form::label('label name', 'Nombre') !!}
                {!! Form::text('name', $user->name,['class' => 'form-control']) !!}
                <br>
                {!! Form::label('label company', 'Empresa') !!}
                {!! Form::select('department', $companies, null, [
                        'class' => 'form-control',
                        'placeholder' => 'Selecciona Departamento',
                    ]) !!}
                    @error('companies')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                 @enderror 
                 
                 {!! Form::submit('GENERAR DOCUMENTO', ['class' => 'btnCreate mt-4']) !!}
                 {!! Form::close() !!}
            </div>

            
                
            <div class="col-6 col-md-7 ml-4">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', $user->name,['class' => 'form-control']) !!}

                
             </div>
                
        {{ $user}}
    </div>
@stop
