@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Mi cuenta</h3>
        
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col mb-6">
                @foreach ($user as $usr)

                @if ($usr->image==null)
                <img class="rounded" style="width: 100%; height:500px; object-fit: cover;"  src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg">
                <br>
                <br>
                <button type="button" class="btnCreate"  data-bs-toggle="modal" data-bs-target="#modalImage">
                    Cambiar imagen
                    <i style="margin-left:5px; " class="fa fa-camera" aria-hidden="true"></i>
                </button>
                @else 
                    <img class="rounded" style="width: 100%; height:500px; object-fit: cover;" src="{{ ($usr->image) }}">
                    <br>
                    <br>
                    <button type="button" class="btnCreate"  data-bs-toggle="modal" data-bs-target="#modalImage">
                        Cambiar imagen
                        <i style="margin-left:5px; " class="fa fa-camera" aria-hidden="true"></i>
                    </button>
                @endif
                    
                @endforeach
            </div>

            <div class="col mb-6">
                @foreach ($user as $usr)
                <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Nombre</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{$usr->name}}" disabled>
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-group-text" id="inputGroup-sizing-default">Apellidos</span>
                    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{$usr->lastname}}" disabled>
                  </div>
                  

                @if ( !empty($usr->employee->position->department->name))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Departamento</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $usr->employee->position->department->name }}" disabled>
                    </div>
                @else 
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Departamento</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                    </div>
                @endif
                 
                @if (!empty($usr->employee->position->name))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Puesto</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $usr->employee->position->name }}" disabled>
                    </div>
                @else 
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Puesto</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                    </div>
                @endif
                    
                @foreach ($contacts as $contact)
                    @if (!empty($contact->num_tel))
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Teléfono</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $contact->num_tel }}" disabled>
                        </div>
                    @else 
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Teléfono</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                        </div>
                    @endif

                    @if (!empty($contact->correo1))
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Correo</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $contact->correo1 }}" disabled>
                        </div>
                    @endif

                    @if (!empty($contact->correo2))
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Correo</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $contact->correo2 }}" disabled>
                        </div>
                    @endif

                    @if (!empty($contact->correo3))
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Correo</span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $contact->correo3 }}" disabled>
                        </div>   
                    @endif

                    @if (!empty($contact->correo4))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Correo</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $contact->correo4 }}" disabled>
                    </div>   
                    @endif

                @endforeach
                  
                
                  

                @endforeach
                
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImageLabel">Seleccione la imagen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'profile.change','enctype' => 'multipart/form-data']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-2 form-group">
                                {!! Form::file('image',  ['class' => 'form-control']) !!}
                            </div>
                            @error('image')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Aceptar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop


@section('styles')
<style>
    .input-group-text{
        background-color: #1A346B;
        color: #ffffff;
    }
</style>
@stop
