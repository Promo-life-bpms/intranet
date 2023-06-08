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
            <div class ="container">
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

                            <div class="row">
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
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('guest[]', 'Usuarios: ', ['class' => 'required'] ) !!}
                                            <div class =" d-flex flex-column mb-3">
                                                <div id="seleccionar"></div>
                                            </div>
                                        </div>                            
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('material', 'Material:')!!}
                                        <br>
                                        {{ Form::checkbox('material[]', 'Sillas', false) }}
                                        {{ Form::label('material[]', 'Sillas') }}
                                        <br>
                                        {{ Form::checkbox('material[]', 'Proyector', false) }}
                                        {{ Form::label('material[]', 'Proyector')}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('chair_loan', 'Sillas:')!!}
                                        {!!Form::number('chair_loan', null,['class'=>'form-control', 'placeholder'=>'Número de sillas que utilizará'])!!}
                                        @error('chair_loan')
                                        <br>
                                        @enderror
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
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="dropdown">
                                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Enviar notifacación
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Sí</a></li>
                                                <li><a class="dropdown-item" href="#">No</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($eventos as $evento)
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('id_sala', 'Nombre de la sala:') !!}
                                        {!!Form::select('id_sala', $boardroom, $evento->boordroms->id, ['class'=>'form-control'] )!!}
                                    </div>
                                </div>
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
                                    {!! Form::select('department_id', $departments, null, ['class' => 'form-control','placeholder' => 'Selecciona el departamento...']) !!}
                                    @error('department_id')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {!! Form::label('guest[]', 'Usuarios: ', ['class' => 'required'] ) !!}
                                            <div class =" d-flex flex-column mb-3">
                                                <div id="seleccionarEditar"></div>
                                            </div>
                                        </div>                            
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('material', 'Material:')!!}
                                        <br>
                                        {{ Form::checkbox('material[]','Sillas','$evento->material') }}
                                        {{ Form::label('material[]', 'Sillas') }}
                                        <br>
                                        {{ Form::checkbox('material[]','Proyector','$evento->material') }}
                                        {{ Form::label('material[]', 'Proyector')}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('chair_loan', 'Sillas:')!!}
                                        {!!Form::number('chair_loan',$evento->chair_loan,['class'=>'form-control', 'placeholder'=>'Número de sillas que utilizará'])!!}
                                        @error('chair_loan')
                                        <br>
                                        @enderror
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
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="dropdown">
                                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
                                                Crear reservación
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#">Sí</a></li>
                                                <li><a class="dropdown-item" href="#">No</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->id==$evento->id_usuario)
                            <div class="modal-footer">
                                {!! Form::submit('Modificar', ['class' => 'btn3 btn-warning']) !!}
                                <form action="{{ route('reserviton.creative.delete', ['id_evento' =>$evento->id]) }}" method="post"></form>
                                <form class="form-delete m-2 mt-0"  action="{{ route('reserviton.creative.delete')}}" method="post">
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
            @endif
            {!! Form::close() !!}
            @endforeach

            
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
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
    

    <script type="text/javascript">
        jQuery(document).ready(function() {
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
                                $('select[name="position"]').append('<option value="' +
                                    key + '">' + value + '</option>');
                            });
                            jQuery('select[name="guest[]"]').empty();
                            jQuery.each(data.users, function(key, value) {
                                $('select[name="guest[]"]').append(
                                    '<option value="' + key + '">' + value +
                                    '</option>');
                            });

                            jQuery.each(data.users, function(key,value) {
                                var newRadio = $('<input>', {
                                    type: 'checkbox',
                                    id: 'radio-' + value,
                                    name: 'guest'+ key,
                                    value: value
                                });
                                
                                // Crea una etiqueta label para el radio
                                var newLabel = $('<label>', {
                                    for: 'radio-',
                                    text: value
                                });

                                console.log(value);
                                // Agrega el nuevo radio y la etiqueta al contenedor
                                $('#seleccionar').append(newRadio).append(newLabel);
                                $('#seleccionar').append('<br>');
                            }); 
                        }
                    });
                }else {
                    $('select[name="position"]').empty();
                }
            });
        });
    </script>
    
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('select[name="department_id"]').on('change', function() {
                var id = jQuery(this).val();
                if (id) {
                    jQuery.ajax({
                        url: '/Position/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            jQuery('select[name="position"]').empty();
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

                            jQuery.each(data.users, function(key,value) {
                                var newRadio = $('<input>', {
                                    type: 'checkbox',
                                    id: 'radio-' + value,
                                    name: 'guest'+ key,
                                    value: value
                                });
                                
                                // Crea una etiqueta label para el radio
                                var newLabel = $('<label>', {
                                    for: 'radio-',
                                    text: value
                                });
                                
                                console.log(value);
                                // Agrega el nuevo radio y la etiqueta al contenedor
                                $('#seleccionarEditar').append(newRadio).append(newLabel);
                                $('#seleccionarEditar').append('<br>');
                            }); 
                        }
                    });
                }else {
                    $('select[name="position"]').empty();
                }
            });
        });
    </script>
   

@endsection

@section ('styles')
    <style>
        .btn1{
            margin-top: 12px;    
            border: 10px;
            width: 100px;
            padding: 10px 10px;
            text-align: center;
            border-radius: 10px;
        }
        .btn2{
            margin-top: 8px; 
            border: 10px;
            width: 100px;
            padding: 10px 10px;
            text-align: center;  
            border-radius: 10px; 
          
        }
        .btn3{
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