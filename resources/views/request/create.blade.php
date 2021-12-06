@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Crear solicitud</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['route' => 'request.store']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type_request', 'Tipo de Solicitud') !!}
                        {!! Form::select('type_request', ['0' => 'Seleccione', '1' => 'Salir durante la Jornada', '2' => 'Faltar a sus labores'], '0', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('payment  ', 'Forma de Pago') !!}
                        {!! Form::select('payment', ['0' => 'Seleccione', '1' => 'Descontar Tiempo/Dia', '2' => 'Pagar Tiempo/Dia', '3' => 'A cuenta de vacaciones'], '0', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('absence', 'Fecha de Ausencia') !!}
                        {!! Form::date('absence', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('admission ', 'Fecha de Reingreso') !!}
                        {!! Form::date('admission', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="mb-2 form-group">
                        {!! Form::label('reason', 'Motivo') !!}
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder'=>'Ingrese el motivo']) !!}
                        @error('reason')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
            </div>
            {!! Form::submit('Crear solicitud', ['class' => 'btnCreate mt-4']) !!}
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
