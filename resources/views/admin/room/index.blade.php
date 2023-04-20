@extends('layouts.app')

@section('content')
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

                        <div class="modal-body">
                            {!! Form::open(['route' => 'reserviton.creative.create', 'enctype' => 'multipart/form-data', 'method'=>'POST']) !!}
                            @csrf

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
                                            @foreach ($salas as $sala)
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
                                        {!! Form::label('end', 'Final') !!}
                                        <input type="datetime-local" id="meeting-time" name="end" class="form-control">
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('number_of_people', 'Personas:')!!}
                                        {!!Form::number('number_of_people', null,['class'=>'form-control', 'placeholder'=>'Ingresa  el númerode personas'])!!}
                                        @error('number_of_people')
                                        <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>      
                                     
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('material', 'Material:')!!}
                                        {!!Form::text('material', null,['class'=>'form-control', 'placeholder'=>'Nombre del material que se utilizará'])!!}
                                        @error('material')
                                        <br>
                                        @enderror
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
                                        <select name="id_sala" class="form-control">
                                            <option value="$evento->id_sala" disabled selected>Seleccione una sala...</option>
                                            @foreach ($salas as $sala)
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

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('number_of_people', 'Personas:')!!}
                                        {!!Form::number('number_of_people',$evento->number_of_people,['class'=>'form-control', 'placeholder'=>'Ingresa  el númerode personas'])!!}
                                        @error('number_of_people')
                                        <br>
                                         @enderror
                                    </div>
                                </div>
                            </div>      
                                     
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('material', 'Material:')!!}
                                        {!!Form::text('material',$evento->material,['class'=>'form-control', 'placeholder'=>'Nombre del matwrial que se utilizará'])!!}
                                        @error('material')
                                        <br>
                                        @enderror
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
                            @if(auth()->user()->id==$evento->id_usuario)
                            <div class="modal-footer">
                                {!! Form::submit('Editar', ['class' => 'btn btn-warning']) !!}
                                <form action="{{ route('reserviton.creative.delete', ['id_evento' =>$evento->id]) }}" method="post"></form>
                                <form class="form-delete m-2 mt-0"  action="{{ route('reserviton.creative.delete')}}" method="post">
                                    {!! Form::text('id_evento', $evento->id,['class'=>'form-control', 'hidden']) !!}
                                    @csrf
                                    <input class="btn btn-danger" type="submit" value="Eliminar" />
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            @endforeach
            @stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El registro se eliminará permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@endsection