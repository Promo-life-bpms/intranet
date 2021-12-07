@extends('layouts.app')

@section('dashboard')

    <div class="contenedor-logo">
        <ul class="logos" style="padding-left: 10px;">
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/bhtrade.png') }}" alt="bhtrade"></a> </li>
            <li><a href="#"><img style="max-width: 80px;" src="{{ asset('/img/promolife.png') }}" alt="promolife"></a> </li>
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/promodreams.png') }}" alt="promodreams"></a>
            </li>
            <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/trademarket.png') }}" alt="trademarket"></a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-8 ">
            <h3>Agregar Empleado</h3>
        </div>


        <div class="card">
            <div class="card-body">
                {!! Form::open(['route' => 'admin.employee.store']) !!}
                <div class="row">
                    <div class="col">
                        {!! Form::label('nombre', 'Nombre del Empleado') !!}
                        {!! Form::text('nombre', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre ']) !!}
                        @error('nombre')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        {!! Form::label('paterno', 'Apellido Paterno') !!}
                        {!! Form::text('paterno', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el/los nombres ']) !!}
                        @error('paterno')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>

                    <div class="col-6">
                        {!! Form::label('materno', 'Apellido Materno') !!}
                        {!! Form::text('materno', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el/los nombres ']) !!}
                        @error('materno')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-4">
                        {!! Form::label('birthday_date', 'Fecha de Cumpleaños') !!}
                        {!! Form::date('birthday_date', null, ['class' => 'form-control']) !!}
                        @error('birthday_date')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                    <div class="col-4">
                        {!! Form::label('fecha_ingreso', 'Fecha de Ingreso') !!}
                        {!! Form::date('fecha_ingreso', null, ['class' => 'form-control']) !!}
                        @error('fecha_ingreso')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                    <div class="col-4">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', ['1' => 'Activo', '0' => 'No Activo'], null, ['class' => 'form-control']) !!}
                        @error('status')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        {!! Form::label('id_contacto', 'Contacto') !!}
                        {!! Form::select('id_contacto', $contacts, null, ['class' => 'form-control']) !!}
                        @error('id_contacto')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                    <div class="col">
                        {!! Form::label('id_user', 'Usuario') !!}
                        {!! Form::select('id_user', $users, null, ['class' => 'form-control']) !!}
                        @error('id_user')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                    {!! Form::submit('CREAR EMPLEADO', ['class' => 'btnCreate mt-4']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="department"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/dropdownlist/getPosition/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            jQuery('select[name="position"]').empty();
                            jQuery.each(data, function(key, value) {
                                $('select[name="position"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="position"]').empty();
                }
            });
        });
    </script>
@stop
