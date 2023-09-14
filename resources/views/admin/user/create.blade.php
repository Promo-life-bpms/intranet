@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear usuario</h3>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
        {!! Form::open(['route' => 'admin.users.store', 'enctype' => 'multipart/form-data']) !!}
        <div class="row">
            <div class="col-md-4">
                <p style="font-size: 24px; font-weight: bold;">Información personal</p>
                <div class="form-group">
                    {!! Form::label('name', 'Nombre') !!}
                    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de usuario']) !!}
                    @error('name')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('lastname', 'Apellidos') !!}
                    {!! Form::text('lastname', null, ['class' => 'form-control', 'placeholder' => 'Ingrese los apellidos']) !!}
                    @error('lastname')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('name', 'Correo') !!}
                    {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el correo de acceso']) !!}
                    @error('email')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="mb-2 form-group">
                        {!! Form::label('image', 'Imágen de Usuario') !!}
                        {!! Form::file('image', ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('birthday_date', 'Fecha de cumpleaños') !!}
                    {!! Form::date('birthday_date', null, ['class' => 'form-control']) !!}
                    @error('birthday_date')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <p style="font-size: 24px; font-weight: bold;">Información complementaria</p>
                <div class="form-group">
                    {!! Form::label('date_admission', 'Fecha de ingreso') !!}
                    {!! Form::date('date_admission', null, ['class' => 'form-control']) !!}
                    @error('date_admission')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="form-group">
                    {!! Form::label('department', 'Departamento') !!}
                    {!! Form::select('department', $departments, null, [
                        'class' => 'form-control',
                        'placeholder' => 'Selecciona Departamento',
                    ]) !!}
                    @error('department')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>

                <div class="form-group">
                    {!! Form::label('position', 'Puesto') !!}
                    {!! Form::select('position', $positions, null, [
                        'class' => 'form-control',
                        'placeholder' => 'Selecciona Puesto',
                    ]) !!}
                    @error('position')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('jefe_directo_id', 'Jefe directo') !!}
                    {!! Form::select('jefe_directo_id', $manager, null, [
                        'class' => 'form-control',
                        'placeholder' => 'Selecciona jefe directo ',
                    ]) !!}

                    @error('jefe_directo_id')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <p style="font-size: 24px; font-weight: bold;">Empresas y Roles que maneja</p>
                <div class="form-group ">
                    {!! Form::label('empresas', 'Empresas a las que pertenece') !!}
                    @foreach ($companies as $company)
                        <div>
                            <label>
                                {!! Form::checkbox('companies[]', $company->id, null, ['class' => 'single-checkbox checkbox-margin form-check-input']) !!}
                                {{ $company->name_company }}
                            </label>
                        </div>
                    @endforeach
                    @error('companies')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::label('roles', 'Roles') !!}
                    @foreach ($roles as $role)
                        <div>
                            <label>
                                {!! Form::checkbox('roles[]', $role->id, null, ['class' => 'single-checkbox checkbox-margin form-check-input']) !!}
                                {{ $role->display_name }}
                            </label>
                        </div>
                    @endforeach
                    @error('roles')
                        <small>
                            <font color="red"> *Este campo es requerido* </font>
                        </small>
                        <br>
                    @enderror
                </div>
            </div>
        </div>
        {!! Form::submit('CREAR USUARIO', ['class' => 'btnCreate mt-4']) !!}
    </div>

    {!! Form::close() !!}
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

                            jQuery('select[name="position"]').empty();
                            jQuery.each(data.positions, function(key, value) {
                                $('select[name="position"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                            jQuery('select[name="jefe_directo_id"]').empty();
                            jQuery.each(data.users, function(key, value) {
                                $('select[name="jefe_directo_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="position"]').empty();
                }
            });
        });
    </script>
    {{-- <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="position"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/user/getManager/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery('select[name="jefe_directo_id"]').empty();
                            jQuery.each(data, function(key, value) {
                                $('select[name="jefe_directo_id"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });
                        }
                    });
                } else {
                    $('select[name="employee_id"]').empty();
                }
            });
        });
    </script> --}}
@stop
