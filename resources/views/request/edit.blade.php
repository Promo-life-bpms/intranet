@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Detalles de la solicitud</h3>
        </div>
        <div class="card-body">

            {!! Form::model($request,['route' => ['request.update',$request], 'method' => 'put']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type_request', 'Tipo de Solicitud') !!}
                        {!! Form::select('type_request', [ 'Salir durante la Jornada' => 'Salir durante la Jornada', 'Faltar a sus labores' => 'Faltar a sus labores'], null, ['class' => 'form-control','placeholder'=>'Seleccione opcion']) !!}
                        @error('type_request')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                       @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('payment', 'Forma de Pago') !!}
                        {!! Form::select('payment', ['Descontar Tiempo/Dia' => 'Descontar Tiempo/Dia', 'Pagar Tiempo/Dia' => 'Pagar Tiempo/Dia', 'A cuenta de vacaciones' => 'A cuenta de vacaciones'], null, ['class' => 'form-control','placeholder'=>'Seleccione opcion']) !!}
                        @error('payment')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('absence', 'Fecha de Ausencia') !!}
                        {!! Form::date('absence', null, ['class' => 'form-control']) !!}
                        @error('absence')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('admission ', 'Fecha de Reingreso') !!}
                        {!! Form::date('admission', null, ['class' => 'form-control']) !!}
                        @error('admission')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col ">
                        {!! Form::label('direct_manager_status', 'Autorizacion Superior') !!}
                        {!! Form::select('direct_manager_status', [ 'Pendiente' => 'Pendiente', 'Aprobado' => 'Aprobado'], null, ['class' => 'form-control','placeholder'=>'Seleccione opcion']) !!}
                        @error('type_request')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                       @enderror
                    </div>

                    <div class="col ">
                        {!! Form::label('human_resources_status', 'Autorizacion RH') !!}
                        {!! Form::select('human_resources_status', [ 'Pendiente' => 'Pendiente', 'Aprobado' => 'Aprobado'], null, ['class' => 'form-control','placeholder'=>'Seleccione opcion']) !!}
                        @error('type_request')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                       @enderror
                    </div>
                </div>

                
                <div class="col-md-12">
                    <div class="mb-2 form-group">
                        {!! Form::label('reason', 'Motivo') !!}
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder'=>'Ingrese el motivo', 'readonly']) !!}
                        @error('reason')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
            </div>
            {!! Form::submit('Actualizar solicitud', ['class' => 'btnCreate mt-4']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/quill/quill.snow.css') }}">
@stop

@section('scripts')
    <script src="{{ asset('assets/vendors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets\js\pages\form-editor.js') }}"></script>
    <script>
        const editor = document.querySelector('.text-desc')
        const textDescription = document.querySelector('.text-description')

        editor.addEventListener('keypress', (e) => {
            console.log('a');
            textDescription.value = editor.firstChild.innerHTML
        })
    </script>
@stop
