@extends('layouts.app')

@section('content')
@if (session('message'))
<div class="alert alert-success">
    {{ session('message') }}
</div>
@elseif (session('message1'))
<div class="alert alert-danger">
    {{ session('message1') }}
</div>
@elseif (session('message2'))
<div class="alert alert-warning">
    {{ session('message2') }}
</div>
@endif
<div class="container">
    <h1>Reservación de la sala recreativa</h1>
    <div id="calendar"></div>
</div>

<div class="modal fade" id="evento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Reservar sala</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {!! Form::open(['route' => 'reserviton.creative.create', 'enctype' => 'multipart/form-data', 'method'=>'POST']) !!}
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::text('id_usuario', $user->name,['class'=>'form-control', 'hidden']) !!}
                            {!!Form::label('title ', 'Título:')!!}
                            {!!Form::text('title', null,['class'=>'form-control', 'placeholder'=>'Ingresa un titulo'])!!}
                            @error('title')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>
                
                @php
                    $esGerente = false;
                    foreach ($gerentes as $gerente) {
                        if ($gerente == auth()->user()->id) {
                            $esGerente = true;
                            break;
                        }
                    }
                @endphp
                
                <div class="row" id="reservation_div" style="{{ $esGerente ? '' : 'display: none;' }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('reservation', 'Reservar toda la sala:')!!}
                            <br>
                            {{ Form::radio('reservation', 'Sí', null, ['id' => 'reservation_si']) }}
                            {{ Form::label('reservation_si', 'Sí') }}
                            {{ Form::radio('reservation', 'No', null, ['id' => 'reservation_no', 'checked' => !$esGerente]) }}
                            {{ Form::label('reservation_no', 'No') }}
                        </div>
                    </div>
                </div>

                <div class="row" id="salas_div" style="{{ $esGerente ? 'display: none;' : '' }}">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('id_sala', 'Nombre de la sala:') !!}
                            <select name="id_sala" class="form-control">
                                <option value="" disabled selected>Seleccione una sala...</option>
                                @foreach ($salitas as $sala)
                                    <option value="{{ $sala->id }}">{{ $sala->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="disclaimer" style="display: none;">
                <p>Reservar la sala entera implica que solo el personal autorizado tiene acceso a la sala, por lo cual, 
                    los cubículos quedan deshabilitados hasta el término de la sesión. </p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('start', 'Inicio:') !!}
                            <input type="datetime-local" id="meeting-time" name="start" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('end', 'Final:') !!}
                            <input type="datetime-local" id="meeting-time" name="end" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm">
                        {!! Form::label('department_id', 'Departamento:') !!}
                        {!! Form::select('department_id', $departments, null, ['class' => 'form-control','placeholder' => 'Selecciona el departamento...']) !!}
                        @error('department_id')
                        <br>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('guest[]', 'Usuarios: ', ['class' => 'required' ] ) !!}
                                <div class=" d-flex flex-column mb-3">
                                    <div id="crear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('chair_loan', 'Cantidad de sillas:') !!}
                            {!! Form::number('chair_loan', 0, ['class' => 'form-control']) !!}
                            @error('chair_loan')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('proyector', 'Cantidad de proyectores:')!!}
                            {!!Form::number('proyector', 0,['class'=>'form-control'])!!}
                            @error('proyector')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('engrave', 'Grabar reunión:') !!}
                            <br>
                            {{ Form::checkbox('engrave', 'Sí', null, ['class' => 'single-checkbox']) }}
                            {{ Form::label('engrave_si', 'Sí') }}
                            <br>
                            {{ Form::checkbox('engrave', 'No', null, ['class' => 'single-checkbox']) }}
                            {{ Form::label('engrave_no', 'No') }}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('description', 'Descripción:')!!}
                            {!!Form::textarea('description', null,['class'=>'form-control'])!!}
                            @error('description')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    {!! Form::submit('Crear reservación', ['class' => 'btn btn-success']) !!}
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@foreach ($eventos as $index=>$evento)
{!! Form::open(['route' => 'reserviton.creative.update', 'enctype' => 'multipart/form-data', 'method'=>'PUT']) !!}
@csrf
@if(auth()->user()->id==$evento->id_usuario)
<div class="modal fade" id="Editar{{$evento->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar evento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::text('id_evento', $evento->id,['class'=>'form-control', 'hidden']) !!}
                            {!! Form::text('id_usuario', $user->id,['class'=>'form-control','hidden']) !!}
                            {!!Form::label('title ', 'Título:')!!}
                            {!!Form::text('title', $evento->title,['class'=>'form-control', 'placeholder'=>'Ingresa un titulo'])!!}
                            @error('title')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                @php 
                    $esGerente = false;
                    foreach ($gerentes as $gerente) {
                        if ($gerente == auth()->user()->id) {
                            $esGerente = true;
                            break;
                        }
                    }
                @endphp
                
                @if ($gerente == auth()->user()->id)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('reservation', 'Reservar toda la sala:') !!}
                                <br>
                                {{ Form::radio('reservation', 'Sí', $evento->reservation == 'Sí', ['id' => 'reservation_si']) }}
                                {{ Form::label('reservation_si', 'Sí') }}
                                {{ Form::radio('reservation', 'No', $evento->reservation == 'No', ['id' => 'reservation_no']) }}
                                {{ Form::label('reservation_no', 'No') }}
                            </div>
                        </div>
                    </div>
                    @else
                    {{ Form::hidden('reservation', 'No') }}
                @endif

                

                <div class="row" id="sala_{{$evento->id}}" @if ($evento->reservation == 'Sí') style="display: none;" @endif>
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('id_sala', 'Nombre de la sala:') !!}
                            {!! Form::select('id_sala', $boardroom, $evento->boordroms->id, ['class'=>'form-control']) !!}
                            @error('id_sala')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="mensaje_{{$evento->id}}" style="{{ $evento->reservation == 'Sí' ? '' : 'display: none;' }}">
                    <p>Reservar la sala entera implica que solo el personal autorizado tiene acceso a la sala, por lo cual, 
                       los cubículos quedan deshabilitados hasta el término de la sesión.</p>
                </div>
                   
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('start', 'Inicio:') !!}
                            <input type="datetime-local" id="meeting-time" value="{{$evento->start}}" name="start" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('end', 'Final:') !!}
                            <input type="datetime-local" id="meeting-time" value="{{$evento->end}}" name="end" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm">
                        {!! Form::label('department_id', 'Departamento:') !!}
                        {!! Form::select('department_id', $departments,null, ['class' => 'form-control','placeholder' => 'Selecciona el departamento...']) !!}
                        @error('department_id')
                        <br>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('guest[]', 'Usuarios: ', ['class' => 'required'] ) !!}
                                <div class=" d-flex flex-column mb-3">
                                    <div id="seleccionarEditar{{$evento->id}}"></div>
                                    {!! Form::label('guest[]', 'Usuarios ya invitados: ', ['class' => 'required'] ) !!}
                                    @foreach ($nameusers[$loop->index] as $nombre)
                                    @if ($index == $loop->parent->index)
                                    <p style="margin: 0; color: #231C63B3; font-weight: bold">{{ trim($nombre) }}</p>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('chair_loan', 'Cantidad de sillas:')!!}
                            {!!Form::number('chair_loan', $evento->chair_loan,['class'=>'form-control'])!!}
                            @error('chair_loan')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('proyector', 'Cantidad de proyectores:')!!}
                            {!!Form::number('proyector', $evento->proyector,['class'=>'form-control'])!!}
                            @error('proyector')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('engrave', 'Grabar reunión:') !!}
                            <br>
                            {{ Form::checkbox('engrave', 'Sí', $evento->engrave == 'Sí', ['class' => 'single-checkbox']) }}
                            {{ Form::label('engrave_si', 'Sí') }}
                            <br>
                            {{ Form::checkbox('engrave', 'No', $evento->engrave == 'No', ['class' => 'single-checkbox']) }}
                            {{ Form::label('engrave_no', 'No') }}
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!!Form::label('description', 'Descripción:')!!}
                            {!!Form::textarea('description',$evento->description,['class'=>'form-control'])!!}
                            @error('description')
                            <br>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(auth()->user()->id==$evento->id_usuario)
                <div class="modal-footer">
                    {!! Form::submit('Modificar', ['class' => 'btn3 btn-warning']) !!}
                    <form action="{{ route('reserviton.creative.delete', ['id_evento' =>$evento->id]) }}" method="post"></form>
                    <form class="form-delete m-2 mt-0" action="{{ route('reserviton.creative.delete')}}" method="post">
                        {!! Form::text('id_evento', $evento->id,['class'=>'form-control', 'hidden']) !!}
                        @csrf
                        <input class="btn1 btn-danger" type="submit" value="Eliminar" />
                    </form>
                    <button type="button" class="btn2 btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@elseif(auth()->user()->id!=$evento->id_usuario)
<div class="modal fade" id="Editar{{$evento->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{($evento->users->name. ' ' .$evento->users->lastname)}} creo la reservación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Título:</b>
                        {{$evento->title. '.'}}
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Nombre de la sala:</b>
                        {{$evento->boordroms->name. '.'}}
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Fecha y hora de inicio:</b>
                        {{$evento->start. '.'}}
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Fecha y hora de fin:</b>
                        {{$evento->end. '.'}}
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Ubicacón de la sala:</b>
                        {{$evento->boordroms->location. '.'}}
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Los invitados a la reunión son:</b>
                        @foreach ($nameusers[$loop->index] as $nombre)
                        @if ($index == $loop->parent->index)
                    <p style="margin: 0; color: #607080;">{{ trim($nombre) }}</p>
                    @endif
                    @endforeach
                    </p>
                </div>
                <div class="modal-body text-left">
                    <p class="m-0">
                        <b>Descripción: </b>
                        {{($evento->description.'.')}}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
{!! Form::close() !!}
@endforeach
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    $('.form-delete').submit(function(e) {
        e.preventDefault();
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡La reservación se eliminará permanentemente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '¡Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        })
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var engraveCheckbox = document.getElementById('engrave-checkbox');
        var emailField = document.getElementById('email-field');
        engraveCheckbox.addEventListener('change', function() {
            emailField.classList.toggle('d-none', !this.checked);
        });
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        var selectAllCheckbox = $('<input>', {
            type: 'checkbox',
            id: 'select-all-checkbox'
        });

        // Crea una etiqueta label para el checkbox "Seleccionar todo"
        var selectAllLabel = $('<label>', {
            for: 'select-all-checkbox',
            text: 'Seleccionar todo'
        });

        // Agrega el <br>, el checkbox y la etiqueta al contenedor
        $('#crear').append('<br>');
        $('#crear').append(selectAllCheckbox);
        $('#crear').append(selectAllLabel);

        // Oculta el checkbox "Seleccionar todo" inicialmente
        selectAllCheckbox.hide();
        selectAllLabel.hide();

        jQuery('select[name="department_id"]').on('change', function() {
            var id = jQuery(this).val();
            if (id) {
                selectAllCheckbox.show();
                selectAllLabel.show();

                jQuery.ajax({
                    url: '/dropdownlist/Position/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        jQuery('select[name="position"]').empty();

                        var usersContainer = $('<div>'); // Crea un contenedor para los usuarios
                        jQuery.each(data.positions, function(key, value) {
                            $('select[name="position"]').append('<option value="' +
                                key + '">' + value + '</option>');
                        });

                        jQuery('select[name="guest[]"]').empty();
                        jQuery.each(data.users, function(key, value) {
                            $('select[name="guest[]"]').append(
                                '<option value="' + key + '">' + value +
                                '</option>');
                        });

                        jQuery.each(data.users, function(key, value) {
                            var newCheckbox = $('<input>', {
                                type: 'checkbox',
                                id: 'checkbox-' + value,
                                name: 'guest' + key,
                                value: value
                            });

                            // Crea una etiqueta label para el checkbox
                            var newLabel = $('<label>', {
                                for: 'checkbox-' + value,
                                text: value
                            });

                            console.log(value);
                            // Agrega el nuevo checkbox y la etiqueta al contenedor de usuarios
                            usersContainer.append(newCheckbox).append(newLabel);
                            usersContainer.append('<br>');
                        });

                        // Agrega el contenedor de usuarios después del checkbox "Seleccionar todo"
                        $('#crear').append(usersContainer);
                    }
                });
            } else {
                $('select[name="position"]').empty();
                selectAllCheckbox.hide();
                selectAllLabel.hide();
            }
        });

        // Controlador de eventos para el checkbox "Seleccionar todo"
        $('#select-all-checkbox').on('change', function() {
            var isChecked = $(this).prop('checked');
            $('input[type="checkbox"][name^="guest"]').prop('checked', isChecked);
        });
    });
</script>

@foreach($eventos as $evento)
<script type="text/javascript">
    jQuery(document).ready(function() {
        var selectAllCheckbox = $('<input>', {
            type: 'checkbox',
            id: 'selectAll',
            name: 'selectAll',
            value: 'selectAll'
        });

        var selectAllLabel = $('<label>', {
            for: 'selectAll',
            text: 'Seleccionar todo'
        });

        // Ocultar el checkbox "Seleccionar todo" y el nombre de usuario inicialmente
        selectAllCheckbox.hide();
        selectAllLabel.hide();
        $('#nombreUsuarios').hide();

        // Agregar el checkbox "Seleccionar todo" y el contenedor de nombres de usuario al contenedor principal
        $('#seleccionarEditar{{$evento->id}}').prepend(selectAllLabel).prepend(selectAllCheckbox).append('<br>').append('<div id="nombreUsuarios"></div>');

        selectAllCheckbox.on('change', function() {
            var isChecked = $(this).prop('checked');
            $('input[name^="guest"]').prop('checked', isChecked);
        });

        jQuery('select[name="department_id"]').on('change', function() {
            var id = jQuery(this).val();
            if (id) {
                jQuery.ajax({
                    url: '/dropdownlist/Position/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        jQuery('select[name="position"]').empty();
                        jQuery.each(data.positions, function(key, value) {
                            jQuery('select[name="position"]').append('<option value="' +
                                key + '">' + value + '</option>');
                        });

                        jQuery('select[name="guest[]"]').empty();
                        $('#nombreUsuarios').empty(); // Vaciar el contenedor de nombres de usuario antes de actualizarlo

                        jQuery.each(data.users, function(key, value) {
                            var newCheckbox = $('<input>', {
                                type: 'checkbox',
                                id: 'check-' + value,
                                name: 'guest' + key,
                                value: value
                            });

                            var newLabel = $('<label>', {
                                for: 'check-' + value,
                                text: value
                            });

                            $('#seleccionarEditar{{$evento->id}}').append(newCheckbox).append(newLabel).append('<br>');
                            // Agregar el nombre del usuario al contenedor de nombres de usuario
                            $('#nombreUsuarios').append(value).append('<br>');
                        });

                        // Mostrar el checkbox "Seleccionar todo" y el contenedor de nombres de usuario cuando se seleccione un departamento
                        selectAllCheckbox.show();
                        selectAllLabel.show();
                        $('#nombreUsuarios').hide();
                    }
                });
            } else {
                $('select[name="position"]').empty();
                $('#seleccionarEditar{{$evento->id}}').empty(); // Vaciar el contenedor si no hay ID seleccionado

                // Ocultar el checkbox "Seleccionar todo" y el contenedor de nombres de usuario si no hay departamento seleccionado
                selectAllCheckbox.hide();
                selectAllLabel.hide();
                $('#nombreUsuarios').hide();
            }
        });
    });
</script>
@endforeach

<script>
    $(document).ready(function() {
        $('.single-checkbox').on('change', function() {
            $('.single-checkbox').not(this).prop('checked', false);
        });
    });
</script>

@foreach($eventos as $evento)
<script>
    $(document).ready(function() {
        $('input[name="reservation"]').change(function() {
            if ($(this).val() === 'No') {
                $('#sala_{{$evento->id}}').show();
            } else {
                $('#sala_{{$evento->id}}').hide();
            }
        });
    });
</script>
@endforeach

@foreach($eventos as $evento)
<script>
    $(document).ready(function() {
        // Manejar el evento de cambio de los radios "Sí" y "No"
        $('input[name="reservation"]').change(function() {
            if ($(this).val() === 'Sí') {
                $('#mensaje_{{$evento->id}}').show(); // Mostrar el disclaimer si se selecciona "Sí"
            } else {
                $('#mensaje_{{$evento->id}}').hide(); // Ocultar el disclaimer si se selecciona "No"
            }
        });

        // Inicializar la visibilidad del disclaimer en función del estado inicial del radio seleccionado
        if ($('input[name="reservation"]:checked').val() === 'Sí') {
            $('#mensaje_{{$evento->id}}').show();
        } else {
            $('#mensaje_{{$evento->id}}').hide();
        }
    });
</script>
@endforeach


<script>
    document.addEventListener('DOMContentLoaded', function() {
        var reservationSi = document.getElementById('reservation_si');
        var reservationNo = document.getElementById('reservation_no');
        var reservationDiv = document.getElementById('reservation_div');
        var salasDiv = document.getElementById('salas_div');
        var selectSala = document.querySelector('select[name="id_sala"]');
        var disclaimer = document.getElementById('disclaimer');

        reservationSi.checked = false; // Desmarcar el radio button 'Sí'

        reservationSi.addEventListener('change', function() {
            salasDiv.style.display = (this.checked && this.value === 'Sí') ? 'none' : 'block';
            disclaimer.style.display = (this.checked && this.value === 'Sí') ? 'block' : 'none';

            if (this.checked && this.value === 'Sí') {
                selectSala.value = selectSala.querySelector('option:not([disabled])').value;
                alert('Reservará toda la sala');
            } else {
                selectSala.value = '';
            }
        });

        reservationNo.addEventListener('change', function() {
            salasDiv.style.display = (this.checked && this.value === 'No') ? 'block' : 'none';
            disclaimer.style.display = 'none';

            if (this.checked && this.value === 'No') {
                selectSala.value = '';
            }
        });
    });
</script>
@endsection

@section ('styles')
<style>
    .btn1 {
        margin-top: 12px;
        border: 10px;
        width: 100px;
        padding: 10px 10px;
        text-align: center;
        border-radius: 10px;
    }

    .btn2 {
        margin-top: 8px;
        border: 10px;
        width: 100px;
        padding: 10px 10px;
        text-align: center;
        border-radius: 10px;

    }

    .btn3 {
        margin-top: 8px;
        border: 10px;
        width: 100px;
        padding: 10px 10px;
        text-align: center;
        border-radius: 10px;
        color: #ffffff;
    }
</style>
@endsection