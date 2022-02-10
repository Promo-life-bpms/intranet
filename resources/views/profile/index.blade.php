@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Mi cuenta</h3>
        
    </div>
    <div class="card-body">

        <div class="row">
            <div class="banner">
                <div class="d-flex justify-content-center">
                    @foreach ($user as $usr)

                    @if ($usr->image==null)

                        <div class="container-image  rounded-circle">
                            <div class="image" style=" ">
                                <img class="profile-picture" src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"  alt=""> 
                            </div>

                            <div class="change-image"  style="z-index: 10;" >
                                
                                <button type="button" class="btnCreate"  data-bs-toggle="modal" data-bs-target="#modalImage">
                                    <i style="margin-left:5px; " class="fa fa-camera" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                    @else 
                        <div class="container-image  rounded-circle">
                            <div class="image" style=" ">
                                <img class="profile-picture" src="{{ ($usr->image) }}"  alt=""> 
                            </div>

                            <div class="change-image"  style="z-index: 10;" >
                                
                                <button type="button" class="btnCreate"  data-bs-toggle="modal" data-bs-target="#modalImage">
                                    <i style="margin-left:5px; " class="fa fa-camera" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                    @endif
                    
                    @endforeach
                </div>
            </div>
        </div>

        <div class="separador" style="margin-top:100px "></div>

        <div class="row">
                <h4>Informacion de usuario</h4>

                @foreach ($user as $usr)
                <div class="col md-4">
                                     
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{$usr->name}}" disabled>
                    </div>
                  
                </div>
                <div class="col md-4">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{$usr->lastname}}" disabled>
                    </div>
                </div>
                
                <div class="col md-4">
                    @if ( !empty($usr->employee->position->department->name))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-building" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $usr->employee->position->department->name }}" disabled>
                    </div>
                    @else 
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">
                                <i class="fa fa-building" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                        </div>
                    @endif
                </div>
              
             
        </div>

        <div class="row">
                <div class="col md-6">
                    @if (!empty($usr->employee->position->name))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-briefcase" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $usr->employee->position->name }}" disabled>
                    </div>
                    @else 
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">
                                <i class="fa fa-briefcase" aria-hidden="true"></i>

                            </span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                        </div>
                    @endif
                </div>
                    
                <div class="col md-6">
                    @if (!empty($usr->email))
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="{{ $usr->email }}" disabled>
                    </div>
                    @else 
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>
                            <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" value="No especificado" disabled>
                        </div>
                    @endif

                </div>
                    
                @endforeach
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

    .banner {
  	height: 45vh;
  	background-image: 
	  linear-gradient(to right bottom, 
     rgba(76, 216, 255, 0.8),
     rgba(30, 108, 217, 0.8)),
     url('http://www.trademarket.com.mx/assets/imgs/quienes.jpg');
  	
	background-size: cover;
  	background-position: top;
  	position: relative;
    
  	clip-path: polygon(0 0, 100% 0, 100% 100vh, 0 100%);
}

.container-image {
    width:240px; 
    height:240px;
    background: #ffffff; 
    margin-top: 17.5%;
    overflow: hidden; 
   
} 

.image{
    width:100%;
    height:100%; 
    z-index:2;
}

.change-image{
    width: 100%; 
    height:60px; 
    margin-top: -20%;
    overflow: hidden;
    z-index: 20;
}

.btnCreate{
    opacity: 0.5;
}

.profile-picture{
    width: 100%;
    height: 100%;
    object-fit: cover;
}

</style>
@stop
