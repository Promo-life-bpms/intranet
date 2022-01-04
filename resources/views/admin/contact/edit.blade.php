@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Contacto</h3>
    </div>
    <div class="card-body">
        {!! Form::model($contact, ['route' => ['admin.contacts.update', $contact], 'method' => 'put']) !!}

            <div class="row ">
                <div class="form-group col">
                    {!! Form::label('num_tel', 'Numero de telefono') !!}
                    {!! Form::text('num_tel', null,  ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono']) !!}
                    @error('num_tel')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row ">
                <div class="form-group col-6">
                    {!! Form::label('correo1', 'Correo Promolife') !!}
                    {!! Form::email('correo1', null,  ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono']) !!}
                    @error('num_tel')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="form-group col-6">
                    {!! Form::label('correo2', 'Correo BH-Trademarket') !!}
                    {!! Form::email('correo2', null,  ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono']) !!}
                    @error('num_tel')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row ">
                <div class="form-group col-6">
                    {!! Form::label('correo3', 'Correo Trademarket') !!}
                    {!! Form::email('correo3', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono']) !!}
                    @error('num_tel')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="form-group col-6">
                    {!! Form::label('correo4', 'Correo PromoDreams') !!}
                    {!! Form::email('correo4', null,  ['class' => 'form-control', 'placeholder' => 'Ingrese el numero de telefono']) !!}
                    @error('num_tel')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>

            {!! Form::submit('ACTUALIZAR CONTACTO', ['class' => 'btnCreate mt-4']) !!}
            
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
