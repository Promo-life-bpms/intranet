@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Reservación de la sala recreativa</h1>
                <div id="calendar"></div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="evento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Reservacón</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            {!! Form::open(['route' => 'reserviton.creative.create', 'enctype' => 'multipart/form-data', 'method'=>'POST']) !!}
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!! Form::text('id:usuario', $user->id,['class'=>'form-control', 'hidden']) !!}
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
                                        {!!Form::label('date', 'Fecha:')!!}
                                        {!!Form::date('date', null,['class'=>'form-control', 'placeholder'=>'Ingresa la fecha'])!!}
                                        @error('date')
                                        <br>
                                        @enderror
                                    </div>
                                </div>
                            </div> 

                            <select name="id_sala" id="id_sala">
                                <option value="id_sala">Selecciona la sala</option>
                                @foreach($salas as $sala)
                                <option value="{{$sala->id}}">{{$sala->name}}</option>
                                @endforeach
                            </select>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('start', 'Inicio:')!!}
                                        {!!Form::time('start', null,['class'=>'form-control', 'placeholder'=>'Ingresa la hora de inicio'])!!}
                                        @error('start')
                                        <br>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {!!Form::label('end', 'Fin:')!!}
                                        {!!Form::time('end', null,['class'=>'form-control', 'placeholder'=>'Ingresa la hora de fin'])!!}
                                        @error('end')
                                        <br>
                                        @enderror
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
                                        {!!Form::text('material', null,['class'=>'form-control', 'placeholder'=>'Nombre del matwrial que se utilizará'])!!}
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
                                {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
                                <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Editar</button>
                                <button type="button" class="btn btn-info" data-bs-dismiss="modal">Elimiar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                                
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
@stop

