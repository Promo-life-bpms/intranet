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
                        {!! Form::label('nombre_solicitud', 'Tipo de Solicitud') !!}
                        {!! Form::select('type', ['0' => 'Seleccione', '1' => 'Salir durante la Jornada', '2' => 'Faltar a sus labores'], '0', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('nombre_solicitud', 'Forma de Pago') !!}
                        {!! Form::select('pay', ['0' => 'Seleccione', '1' => 'Descontar Tiempo/Dia', '2' => 'Pagar Tiempo/Dia', '3' => 'A cuenta de vacaciones'], '0', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('fecha_solicitud', 'Fecha de Ausencia') !!}
                        {!! Form::date('fecha_solicitud', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('fecha_solicitud', 'Fecha de Reingreso') !!}
                        {!! Form::date('fecha_solicitud', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-2 form-group">
                        <label for="exampleFormControlTextarea1" class="form-label">Motivo</label>
                        <div id="full" class="text-desc">
                            <p>Descripcion de tu ausencia!</p>
                            <br>
                        </div>
                        <input type="hidden" name="description" class="text-description">
                        @error('reason')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
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
