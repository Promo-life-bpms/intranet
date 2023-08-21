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
                                        {!!Form::label('title ', 'Título:', ['class' => 'required'])!!}
                                        {!!Form::text('title', null,['class'=>'form-control', 'placeholder'=>'Ingresa un titulo'])!!}
                                        @error('title')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        
                            @php
                                $esGerente = true;
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
                                        @error('reservation')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
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
                                        @error('id_sala')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
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
                                        @error('start')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('end', 'Final:') !!}
                                        <input type="datetime-local" id="meeting-time" name="end" class="form-control">
                                        @error('end')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div id="selected-users-div"></div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="row form-group">
                                <div class="col-sm">
                                    {!! Form::label('department_id', 'Departamento:') !!}  
                                    {!! Form::select('department_id', $departments, null, ['class' => 'form-control','placeholder' => 'Selecciona el departamento...']) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('guest[]', 'Usuarios:', ['class' => 'required']) !!}
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select-all-checkbox" disabled>
                                            <label class="form-check-label"  for="select-all-checkbox">
                                                Seleccionar todo
                                            </label>
                                        </div>
                                        <div class="d-flex flex-column mb-3">
                                            <div id="crear"></div>
                                            @error('guest')
                                                <small>
                                                    <font color="red"> *Este campo es requerido* </font>
                                                </small>
                                                <br>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('chair_loan', 'Cantidad de sillas:') !!}
                                        {!! Form::number('chair_loan', 0, ['class' => 'form-control', 'id' => 'chair_loan_input', 'min' => 0]) !!}
                                        @error('chair_loan')
                                            <small>
                                                <font color="red"> *La cantidad de sillas no puede ser menor a cero* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div id="mensaje_sillas" style="display: none;">
                                <p>Considera que al solicitar sillas el tiempo de la reservación debe realizarse con un mínimo de dos días de anticipación.</p>
                            </div>
                         
                            <div id="mensaje_sillas2" style="display: none;">
                                <p>No puedes elegir una cantidad de sillas con valor negativo.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('proyector', 'Cantidad de proyectores:')!!}
                                        {!!Form::number('proyector', 0,['class'=>'form-control', 'id' => 'proyectoresmensaje', 'min' => 0])!!}
                                    </div>
                                </div>
                            </div>

                            <div id="mensaje_proyectores" style="display: none;">
                                <p>No puedes elegir una cantidad de proyectores con valor negativo.</p>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::label('engrave', 'Grabar reunión:') !!}
                                        <br>
                                        {{ Form::checkbox('engrave', 'Sí', null, ['class' => 'single-checkbox', 'id' => 'engrave-checkbox']) }}
                                        {{ Form::label('engrave_si', 'Sí') }}
                                        <br>
                                        {{ Form::checkbox('engrave', 'No', null, ['class' => 'single-checkbox', 'id' => 'no-engrave-checkbox']) }}
                                        {{ Form::label('engrave_no', 'No') }}
                                        @error('engrave')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div id="mensaje-div" style="display: none;">
                                Recuerda que sí deseas que se grabe tu reunión debes crear la reservación con cinco días de anticipación.
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('description', 'Descripción:')!!}
                                        {!!Form::textarea('description', null,['class'=>'form-control'])!!}
                                        @error('description')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                            <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                {!! Form::submit('Crear reservación', ['class' => 'btn btn-success', 'onclick' => 'submitForm()']) !!}
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
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
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @php 
                                        $esGerente = true;
                                        foreach ($gerentes as $gerente) {
                                            if ($gerente == auth()->user()->id) {
                                                $esGerente = true;
                                                break;
                                            }
                                        }
                                    @endphp
                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('reservation', 'Reservar toda la sala:') !!}
                                                <br>
                                                {{ Form::radio('reservation', 'Sí', $evento->reservation == 'Sí', ['id' => 'reservation_si']) }}
                                                {{ Form::label('reservation_si', 'Sí') }}
                                                {{ Form::radio('reservation', 'No', $evento->reservation == 'No', ['id' => 'reservation_no']) }}
                                                {{ Form::label('reservation_no', 'No') }}
                                                @error('reservation')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                        

                                    <div class="row" id="sala_{{$evento->id}}" @if ($evento->reservation == 'Sí') style="display: none;" @endif>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('id_sala', 'Nombre de la sala:') !!}
                                                {!! Form::select('id_sala', $boardroom, $evento->boordroms->id, ['class'=>'form-control']) !!}
                                                @error('id_sala')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
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
                                                @error('start')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('end', 'Final:') !!}
                                                <input type="datetime-local" id="meeting-time" value="{{$evento->end}}" name="end" class="form-control">
                                                @error('end')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('guest[]', 'Usuarios ya invitados: ', ['class' => 'required'] ) !!}
                                                @foreach ($nameusers[$loop->index] as $nombre)
                                                    @if ($index == $loop->parent->index)
                                                        <p style="margin: 0; color: #231C63B3; font-weight: bold">{{ trim($nombre) }}</p>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div id="usuariosSeleccionadosDiv{{$evento->id}}"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <div class="col-sm">
                                            {!! Form::label('department', 'Departamento:') !!}
                                            {!! Form::select('department', $departments,null, ['class' => 'form-control','placeholder' => 'Selecciona el departamento...']) !!}
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    {!! Form::label('guest[]', 'Usuarios: ', ['class' => 'required'] ) !!}
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="select-all-users-{{$evento->id}}"  disabled>
                                                                <label class="form-check-label" for="select-all-users">Seleccionar todo</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" d-flex flex-column mb-3">
                                                        <div id="seleccionarEditar{{$evento->id}}"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('chair_loan', 'Cantidad de sillas:') !!}
                                                {!! Form::number('chair_loan', $evento->chair_loan, ['class' => 'form-control', 'id' => 'chair-'.$evento->id, 'min' => 0]) !!}
                                            </div>
                                        </div>
                                    </div>
                
                                    <div id="mensaje-{{ $evento->id }}" style="display: {{ $evento->chair_loan > 0 ? 'block' : 'none' }};">
                                        <p>Considera que al solicitar sillas el tiempo de la reservación debe realizarse con un mínimo de dos días de anticipación.</p>
                                    </div>

                                    <div id="mensaje2-{{ $evento->id }}" style="display: {{ $evento->chair_loan < 0 ? 'block' : 'none' }};">
                                        <p>No puedes elegir una cantidad de sillas con valor negativo.</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!!Form::label('proyector', 'Cantidad de proyectores:')!!}
                                                {!!Form::number('proyector', $evento->proyector,['class'=>'form-control','id' => 'proyec-'.$evento->id, 'min' => 0])!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="mensajesi-{{ $evento->id }}" style="display: {{ $evento->proyector > 0 ? 'block' : 'none' }};">
                                        <p>No puedes elegir una cantidad de proyectores con valor negativo.</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('engrave', 'Grabar reunión:') !!}
                                                <br>
                                                {{ Form::radio('engrave', 'Sí', $evento->engrave == 'Sí', ['class' => 'single-checkbox', 'id' => 'engrave-checkbox-' . $evento->id, 'onclick' => 'toggleGrabarDiv(' . $evento->id . ')']) }}         
                                                {{ Form::label('engrave_si', 'Sí') }}
                                                <br>
                                                {{ Form::radio('engrave', 'No', $evento->engrave == 'No', ['class' => 'single-checkbox', 'id' => 'engrave-checkbox-no-' . $evento->id, 'onclick' => 'toggleGrabarDiv(' . $evento->id . ')']) }}
                                                {{ Form::label('engrave_no', 'No') }}
                                                @error('engrave')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div id="grabar-{{ $evento->id }}" style="display: {{ $evento->engrave == 'Sí' ? 'block' : 'none' }}">
                                        Sí deseas que tu reunión se grabe debes crear tu reserva con cinco días de anticipación.
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!!Form::label('description', 'Descripción:')!!}
                                                {!!Form::textarea('description',$evento->description,['class'=>'form-control'])!!}
                                                @error('description')
                                                    <small>
                                                        <font color="red"> *Este campo es requerido* </font>
                                                    </small>
                                                    <br>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    @if(auth()->user()->id==$evento->id_usuario)
                                        <div class="modal-footer">
                                            {!! Form::submit('Modificar', ['class' => 'btn3 btn-warning', 'onclick' => 'submitsubmitFormEditar()']) !!}
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

                                        <div class="modal-body text-left">
                                            <p class="m-0">
                                                <b>Se solicitó grabar la reunión: </b>
                                                {{($evento->engrave.'.')}}
                                                <br>
                                                <b>Nota: Sí deseas grabar tu reunión debes crear tu reservación con cinco días de anticipación.</b>
                                            </p>
                                        </div>
                                        
                                        <div class="modal-body text-left">
                                            <p class="m-0">
                                                <b>Reservo toda la sala:</b> {{($evento->reservation.'.')}}
                                                <br>
                                                @if($evento->reservation=='Sí')
                                                <b>Nota:En este horario no podrás acceder ni reservar la sala recreativa, esto incluye a los cubículos y solo el 
                                                        personal autorizado puede acceder a ella.
                                                </b>
                                                @endif
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
        function submitForm() {
            // Obtener los valores de los campos que deseas verificar
            const title = document.querySelector('input[name="title"]').value;          
            const id_sala = document.querySelector('select[name="id_sala"]').value;       
            const start = document.querySelector('input[name="start"]').value;        
            const end = document.querySelector('input[name="end"]').value;
            const description = document.querySelector('textarea[name="description"]').value;
            const engrave = document.querySelector('input[name="engrave"]').value;
            const reservation  = document.querySelector('input[name="reservation"]').value;

            // Verificar si los campos obligatorios están vacíos
            if (!title || !id_sala || !start || !end || !description || !engrave || !reservation) {
                // Mostrar la alerta
                alert("No se creó la reservación. Por favor, asegúrate de completar todos los campos del formulario.");
                return false; // Evitar que el formulario se envíe
            }
        }
    </script>
    
    <script>
        function submitsubmitFormEditar() {
            // Obtener los valores de los campos que deseas verificar
            const title = document.querySelector('input[name="title"]').value;          
            const id_sala = document.querySelector('select[name="id_sala"]').value;       
            const start = document.querySelector('input[name="start"]').value;        
            const end = document.querySelector('input[name="end"]').value;
            const description = document.querySelector('textarea[name="description"]').value;
            const engrave = document.querySelector('input[name="engrave"]').value;
            
            // Verificar si los campos obligatorios están vacíos
            if (!title || !id_sala || !start || !end || !description || !engrave) {
                // Mostrar la alerta
                alert("Asegúrate de completar todos los campos del formulario de lo contrario no se editará el evento. Sí lo realizaste ignora el mensaje.");
                return false; // Evitar que el formulario se envíe
            }
        }
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
            // Conjunto para almacenar los usuarios seleccionados de todos los departamentos
            var allSelectedUsers = new Set(); 
            // Conjunto para almacenar los usuarios seleccionados del departamento actual (los mostrados en pantalla)
            var currentDepartmentSelectedUsers = new Set();
            
            function updateSelectedUsersDiv() {
                var selectedUsersDiv = $('#selected-users-div');
                selectedUsersDiv.empty();
                
                if (allSelectedUsers.size > 0) {
                    selectedUsersDiv.append('<p>Usuarios seleccionados:</p>');
                    selectedUsersDiv.append('<ul>');
                    allSelectedUsers.forEach(function(user) {
                        selectedUsersDiv.find('ul').append('<li>' + user + '</li>');
                    });
                    selectedUsersDiv.append('</ul>');
                }else{
                    selectedUsersDiv.append('<p>No se han seleccionado usuarios.</p>');
                }
            }

            function fetchUsersByDepartment(id) {
                if (id) {
                    jQuery.ajax({
                        url: '/dropdownlist/Position/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            jQuery('select[name="position"]').empty();
                            
                            var usersContainer = $('<div>'); // Crea un contenedor para los usuarios del departamento actual
                            
                            // Agrega los nuevos usuarios del departamento seleccionado al contenedor
                            jQuery.each(data.users, function(key, value) {
                                if (!allSelectedUsers.has(value)) {
                                    var newCheckbox = $('<input>', {
                                        type: 'checkbox',
                                        id: 'checkbox-' + value,
                                        name: 'guest[]',
                                        value: value,
                                        class: 'checkbox-margin form-check-input'
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
                                }
                            });
                            
                            // Agrega los usuarios seleccionados de otros departamentos al contenedor
                            allSelectedUsers.forEach(function(user) {
                                var newCheckbox = $('<input>', {
                                    type: 'checkbox',
                                    id: 'checkbox-' + user,
                                    name: 'guest[]',
                                    value: user,
                                    class: 'checkbox-margin',
                                    checked: true, // Marca el checkbox ya que el usuario está seleccionado
                                    class: 'checkbox-margin form-check-input' 
                                });
                                
                                // Crea una etiqueta label para el checkbox
                                var newLabel = $('<label>', {
                                    for: 'checkbox-' + user,
                                    text: user
                                });

                                console.log(user);
                                // Agrega el nuevo checkbox y la etiqueta al contenedor de usuarios
                                usersContainer.append(newCheckbox).append(newLabel);
                                usersContainer.append('<br>');
                            });

                            // Vaciar el contenedor "crear" antes de agregar los usuarios del nuevo departamento
                            $('#crear').empty();
                            // Agrega el contenedor de usuarios después del checkbox "Seleccionar todo"
                            $('#crear').append(usersContainer);
                            // Habilitar o deshabilitar el checkbox "Seleccionar todo" según el departamento seleccionado
                            if (id) {
                                $('#select-all-checkbox').prop('disabled', false);
                            } else {
                                $('#select-all-checkbox').prop('disabled', true);
                            }
                        }
                    });
                } else {
                    $('select[name="position"]').empty();
                }
            }
            
            // Controlador de eventos para el campo "department_id"
            jQuery('select[name="department_id"]').on('change', function() {
                var id = jQuery(this).val();
                fetchUsersByDepartment(id);
            });
            
            // Controlador de eventos para el checkbox "Seleccionar todo"
            $('#select-all-checkbox').on('change', function() {
                if ($(this).prop('disabled')) return; // No hacer nada si el checkbox está deshabilitado
                var isChecked = $(this).prop('checked');
                // Marcar o desmarcar solo los usuarios del departamento actual que se muestran en pantalla
                $('#crear').find('input[type="checkbox"]').each(function() {
                    $(this).prop('checked', isChecked);
                    var value = $(this).val();
                    if (isChecked) {
                        currentDepartmentSelectedUsers.add(value); // Agregar el usuario seleccionado al conjunto del departamento actual
                        allSelectedUsers.add(value); // Agregar el usuario seleccionado al conjunto de usuarios seleccionados de todos los departamentos
                    } else {
                        currentDepartmentSelectedUsers.delete(value); // Eliminar el usuario deseleccionado del conjunto del departamento actual
                        allSelectedUsers.delete(value); // Eliminar el usuario deseleccionado del conjunto de usuarios seleccionados de todos los departamentos
                    }
                });
                updateSelectedUsersDiv(); // Actualiza el contenido del div con los usuarios seleccionados
            });
            
            // Controlador de eventos para los checkboxes de usuarios dentro del div "crear"
            $('#crear').on('change', 'input[type="checkbox"][name^="guest"]', function() {
                var isChecked = $(this).prop('checked');
                var value = $(this).val();
                if (isChecked) {
                    currentDepartmentSelectedUsers.add(value); // Agregar el usuario seleccionado al conjunto del departamento actual
                    allSelectedUsers.add(value); // Agregar el usuario seleccionado al conjunto de usuarios seleccionados de todos los departamentos
                } else {
                    currentDepartmentSelectedUsers.delete(value); // Eliminar el usuario deseleccionado del conjunto del departamento actual
                    allSelectedUsers.delete(value); // Eliminar el usuario deseleccionado del conjunto de usuarios seleccionados de todos los departamentos
                }
                updateSelectedUsersDiv(); // Actualiza el contenido del div con los usuarios seleccionados
            });
        });
    </script>

    @foreach($eventos as $evento)
        <script type="text/javascript">
        jQuery(document).ready(function() {
            var allSelectedUsers = new Set();

            function updateSelectedUsersDiv() {
                var selectedUsersDiv = $('#usuariosSeleccionadosDiv{{$evento->id}}');
                selectedUsersDiv.empty();
                
                if (allSelectedUsers.size > 0) {
                    selectedUsersDiv.append('<p>Usuarios seleccionados:</p>');
                    selectedUsersDiv.append('<ul>');
                    allSelectedUsers.forEach(function(user) {
                        selectedUsersDiv.find('ul').append('<li>' + user.name + '</li>');
                    });
                    selectedUsersDiv.append('</ul>');
                } else {
                    selectedUsersDiv.append('<p>No se han seleccionado usuarios.</p>');
                }
                
                // Actualizar la variable guest[] con los usuarios seleccionados
                var selectedUsersArray = Array.from(allSelectedUsers, user => user.id);
                $('input[name="guest[]"]').val(selectedUsersArray);
            }
            
            function fetchUsersByDepartment(id) {
                if (id) {
                    jQuery.ajax({
                        url: '/dropdownlist/Position/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            jQuery('select[name="position"]').empty();
                            var usersContainer = $('<div>'); // Crea un contenedor para los usuarios del departamento actual
                            // Agregar los usuarios seleccionados de todos los departamentos al contenedor para el departamento actual
                            allSelectedUsers.forEach(function(user) {
                                var newCheckbox = $('<input>', {
                                    type: 'checkbox',
                                    id: 'check-' + user.id,
                                    name: 'guest' + user.id,
                                    value: user.name,
                                    checked: true, // Marcar como seleccionado si el usuario está en el conjunto de usuarios seleccionados de todos los departamentos
                                    class: 'checkbox-margin form-check-input'
                                });

                                newCheckbox.on('change', function() {
                                    var isChecked = $(this).prop('checked');
                                    var value = $(this).val();
                                    if (!isChecked) {
                                        allSelectedUsers.delete(user); // Eliminar el usuario deseleccionado del conjunto de usuarios seleccionados de todos los departamentos
                                    } else {
                                        allSelectedUsers.add(user); // Agregar el usuario seleccionado al conjunto de usuarios seleccionados de todos los departamentos
                                    }
                                    updateSelectedUsersDiv(); // Actualiza el contenido del div con los usuarios seleccionados
                                });

                                var newLabel = $('<label>', {
                                    for: 'check-' + user.id,
                                    text: user.name
                                });

                                usersContainer.append(newCheckbox).append(newLabel).append('<br>');
                            });
                            // Agregar los nuevos usuarios del departamento seleccionado al contenedor
                            jQuery.each(data.users, function(key, value) {
                                if (!allSelectedUsers.has(value)) {
                                    var user = { id: key, name: value }; 
                                    var newCheckbox = $('<input>', {
                                        type: 'checkbox',
                                        id: 'check-' + value,
                                        name: 'guest' + key,
                                        value: value,
                                        class: 'checkbox-margin form-check-input'
                                    });
                                    
                                    newCheckbox.on('change', function() {
                                        var isChecked = $(this).prop('checked');
                                        var value = $(this).val();
                                        if (!isChecked) {
                                            allSelectedUsers.delete(user); // Eliminar el usuario deseleccionado del conjunto de usuarios seleccionados de todos los departamentos
                                        } else {
                                            allSelectedUsers.add(user); // Agregar el usuario seleccionado al conjunto de usuarios seleccionados de todos los departamentos
                                        }
                                        updateSelectedUsersDiv(); // Actualizar el contenido del div con los usuarios seleccionados
                                    });
                                    var newLabel = $('<label>', {
                                        for: 'check-' + value,
                                        text: value
                                    });
                                    usersContainer.append(newCheckbox).append(newLabel).append('<br>');
                                }
                            });
                            $('#seleccionarEditar{{$evento->id}}').empty();
                            $('#seleccionarEditar{{$evento->id}}').append(usersContainer);
                        }
                    });
                    
                    // Mostrar el checkbox "Seleccionar todo" solo cuando se elija un departamento
                    $('#select-all-users-{{$evento->id}}').show();
                    // Controlar el evento cuando se marque o desmarque el checkbox "Seleccionar todo"
                    $('#select-all-users-{{$evento->id}}').on('change', function() {
                        var isChecked = $(this).prop('checked');

                        // Marcar o desmarcar solo los usuarios visibles en pantalla
                        $('#seleccionarEditar{{$evento->id}} input:visible[type="checkbox"]').prop('checked', isChecked);
                        // Actualizar el conjunto de usuarios seleccionados de todos los departamentos
                        allSelectedUsers.clear();
                        $('#seleccionarEditar{{$evento->id}} input:visible[type="checkbox"]:checked').each(function() {
                            allSelectedUsers.add({ id: $(this).attr('name').substring(5), name: $(this).val() });
                        });
                        updateSelectedUsersDiv(); // Actualizar el contenido del div con los usuarios seleccionados
                    });
                    // Al cargar la página, actualizar el div con los usuarios seleccionados
                    updateSelectedUsersDiv();
                } else {
                    // Si no se eligió un departamento, ocultar el checkbox "Seleccionar todo" y desmarcar los usuarios
                    $('#select-all-users-{{$evento->id}}').hide();
                    $('#select-all-users-{{$evento->id}}').prop('checked', false);
                    $('#seleccionarEditar{{$evento->id}} input[type="checkbox"]').prop('checked', false);
                    allSelectedUsers.clear();
                    $('select[name="position"]').empty();
                    $('#seleccionarEditar{{$evento->id}}').empty();
                }
                if (id) {
                    $('#select-all-users-{{$evento->id}}').prop('disabled', false);
                } else {
                    $('#select-all-users-{{$evento->id}}').prop('disabled', true);
                }
            }
            // Controlador de eventos para el campo "department_id"
            jQuery('select[name="department"]').on('change', function() {
                var id = jQuery(this).val();
                fetchUsersByDepartment(id);
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
                        $('#mensaje_{{$evento->id}}').show();
                    } else {
                        $('#mensaje_{{$evento->id}}').hide();
                    }
                });
            
                // Inicializar la visibilidad del disclaimer en función del estado inicial del radio seleccionado
                var initialReservation = "{{ $evento->reservation }}"; // Obtener el valor de reservation desde PHP
        
                if (initialReservation === 'Sí') {
                    $('#mensaje_{{$evento->id}}').show();
                } else {
                    $('#mensaje_{{$evento->id}}').hide();
                }
            });
        </script>
    @endforeach


    <script>
        jQuery(document).ready(function() {
            // Controlador de eventos para el input "chair_loan"
            jQuery('#chair_loan_input').on('input', function() {
                var cantidadSillas = parseInt(jQuery(this).val());
                // Verifica si la cantidad de sillas es mayor que 0
                if (cantidadSillas > 0) {
                    // Muestra el div "mensaje_hola" si la cantidad de sillas es mayor que 0
                    jQuery('#mensaje_sillas').show();
                } else {
                    // Oculta el div "mensaje_hola" si la cantidad de sillas es 0 o menor
                    jQuery('#mensaje_sillas').hide();
                }
                if (cantidadSillas < 0) {
                    // Muestra el div "mensaje_hola" si la cantidad de sillas es menor que 0
                    jQuery('#mensaje_sillas2').show();
                } else {
                    // Oculta el div "mensaje_hola" si la cantidad de sillas es 0 o menor
                    jQuery('#mensaje_sillas2').hide();
                }
            });
        });
    </script>
    
    <script>
        jQuery(document).ready(function() {
            // Controlador de eventos para el input "chair_loan"
            jQuery('#proyectoresmensaje').on('input', function() {
                var cantidadSillas = parseInt(jQuery(this).val());
                if (cantidadSillas < 0) {
                    jQuery('#mensaje_proyectores').show();
                } else {
                    jQuery('#mensaje_proyectores').hide();
                }
            });
        });
    </script>

    <script>
        const engraveCheckbox = document.getElementById('engrave-checkbox');
        const noEngraveCheckbox = document.getElementById('no-engrave-checkbox');
        const mensajeDiv = document.getElementById('mensaje-div');
        
        engraveCheckbox.addEventListener('change', function () {
            if (this.checked) {
                mensajeDiv.style.display = 'block';
            } else if (!noEngraveCheckbox.checked) {
                mensajeDiv.style.display = 'none';
            }
        });
        
        noEngraveCheckbox.addEventListener('change', function () {
            if (this.checked) {
                mensajeDiv.style.display = 'none';
            } else if (engraveCheckbox.checked) {
                mensajeDiv.style.display = 'block';
            }
        });
    </script>
     
    @foreach($eventos as $evento)
        <script>
            function toggleGrabarDiv(eventId) {
                var engraveCheckbox = document.getElementById('engrave-checkbox-' + eventId);
                var grabarDiv = document.getElementById('grabar-' + eventId);
                grabarDiv.style.display = engraveCheckbox.checked ? 'block' : 'none';
            }
        </script>
    @endforeach

    <script>
        jQuery(document).ready(function() {
            // Controlador de eventos para los inputs de cantidad de sillas
            jQuery('input[id^="chair-"]').on('input', function() {
                var eventId = jQuery(this).attr('id').split('-')[1];
                var cantidadSillas = parseInt(jQuery(this).val());

                // Verifica si la cantidad de sillas es mayor que 0
                if (cantidadSillas > 0) {
                    // Muestra el mensaje para el evento correspondiente
                    jQuery('#mensaje-' + eventId).show();
                } else {
                    // Oculta el mensaje para el evento correspondiente
                    jQuery('#mensaje-' + eventId).hide();
                }

                if(cantidadSillas < 0){
                    // Muestra el mensaje para el evento correspondiente
                    jQuery('#mensaje2-' + eventId).show();
                } else {
                    // Oculta el mensaje para el evento correspondiente
                    jQuery('#mensaje2-' + eventId).hide();
                }
                
            });
        });
    </script>

    <script>
        jQuery(document).ready(function() {
            // Controlador de eventos para los inputs de cantidad de sillas
            jQuery('input[id^="proyec-"]').on('input', function() {
                var eventId = jQuery(this).attr('id').split('-')[1];
                var cantidadProyectores = parseInt(jQuery(this).val());
            
                if (cantidadProyectores >= 0) {
                    // Oculta el mensaje para el evento correspondiente si la cantidad es mayor a 0
                    jQuery('#mensajesi-' + eventId).hide();
                } else {
                    // Muestra el mensaje para el evento correspondiente si la cantidad es menor o igual a 0
                    jQuery('#mensajesi-' + eventId).show();
                }
            });

            // Oculta el mensaje para los eventos que tienen proyector >= 0 al cargar la página
            jQuery('input[id^="proyec-"]').each(function() {
                var eventId = jQuery(this).attr('id').split('-')[1];
                var cantidadProyectores = parseInt(jQuery(this).val());
            
                if (cantidadProyectores >= 0) {
                    jQuery('#mensajesi-' + eventId).hide();
                } else {
                    // Muestra el mensaje para el evento correspondiente si la cantidad es menor o igual a 0
                    jQuery('#mensajesi-' + eventId).show();
                }
            });
        });
    </script>

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
        .checkbox-margin {
            margin-right: 6px;
        }

    </style>
@endsection