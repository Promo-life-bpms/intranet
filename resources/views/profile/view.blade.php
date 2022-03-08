@extends('layouts.app')

@section('content')
    
    <div class="card-body">
        
        <div class="row">

            <div class="banner">
                <div class="d-flex justify-content-flex">
                    @foreach ($user as $usr)

                    @if ($usr->image==null)

                        <div class="container-image  rounded-circle">
                            <div class="image" style=" ">
                                <img class="profile-picture" src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"  alt=""> 
                            </div>

                            <div class="change-image"  style="z-index: 10;" >
                                
                                <button type="button" class="btnCreate"  data-bs-toggle="modal" data-bs-target="#modalImage">
                                    <i style="margin-left:5px; " class="fa fa-camera fa-2x" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                    @else 
                        <div class="container-image  rounded-circle">
                            <div class="image" style=" ">
                                <img class="profile-picture" src="{{  asset('') . $usr->image }}"  alt=""> 
                            </div>

                           
                        </div>

                    @endif
                    
                    @endforeach

                    <div class="container-text  text">
                        <h3><td>{{ $usr->name . ' ' . $usr->lastname }}</td></h3>
                        <h5>{{$usr->employee->position->name}} </h5>
                    </div>

                </div>
            </div>

        </div>

        <div class="separador" style="margin-top:100px "></div>

        <div class="row">

            <div class="col-md-4 " >
                <div class="card bg-light border-light mb-3 p-4"  >
                    @foreach ($user as $usr)
                        <div class="input-group">
                            <span>
                                <i class="fa fa-building " aria-hidden="true"></i>
                            </span>

                            @if ( !empty($usr->employee->position->department->name))
                                <p style="padding-left: 10px;"> {{ $usr->employee->position->department->name }}</p>
                            @else 
                                <p>Sin departamento especificado</p>
                            @endif
                        </div>

                        <div class="input-group">
                            <span>
                                <i class="fa fa-briefcase" aria-hidden="true"></i>
                            </span>

                            @if ( !empty($usr->employee->position->name ))
                                <p style="padding-left: 10px;">{{ $usr->employee->position->name }} </p>
                            @else 
                                <p>Sin puesto especificado</p>
                            @endif
                        </div>

                        <div class="input-group">
                            <span>
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>

                            @if ( !empty($usr->email))
                                <p style="padding-left: 10px;">{{ $usr->email }} </p>
                            @else 
                                <p>Sin correo especificado</p>
                            @endif
                        </div>

                    @endforeach
                </div>
            </div>

            <div class="col-md-8 bg-light border-light p-3">
             
                <div class="col-md-12 p-0">
                     
                    @if (count($publications) <= 0)
                        <p>No hay Publicaciones</p>
                    @else
                        @foreach ($publications as $publication)
                            <div class="m-0 p-0"  style="border-radius:20px;">
                                <div class="card p-3 box">
                                    <div class="d-flex head">
                                        <div class="imagen px-1">
                                            <div class="avatar avatar-xl">
                                                <div class="card-photo" style="width: 40px; height:40px;">
                                                    @if (auth()->user()->image == null)
                                                        <a style="color: inherit;"  href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <p
                                                                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                <span>{{ substr($publication->user->name, 0, 1) . substr($publication->user->lastname, 0, 1) }}</span>
                                                            </p>
                                                        </a>
                                                    @else
                                                        <a style="color: inherit;"  href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <img style="width: 100%; height:100%; object-fit: cover;"
                                                                src="{{ asset($publication->user->image) }}">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nombre">
                                            <p class="m-0 " style="font-weight: bold">
                                                {{ $publication->user->name . ' ' . $publication->user->lastname }}
                                            </p>
                                            <p class="m-0">
                                                {{ $publication->created_at->diffForHumans() }} </p>
                                        </div>
                                    </div>
                                    <p class="mt-4 ">
                                        {{ $publication->content_publication }} </p>
                                    @if ($publication->photo_public)
                                        <img src="{{ asset('storage') . '/' . $publication->photo_public }}" alt=""
                                        width="auto">
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div id="boton">
                                            <like-button style="margin-top: -24px; overflow:hidden;" publication-id="{{ $publication->id }}"
                                                like="{{ auth()->user()->meGusta->contains($publication->id) }}"
                                                likes="{{ $publication->like->count() }}">
                                            </like-button>
                                        </div>
    
                                    </div>
                                       
                                    <a  style="font-size:18px; color:#000000;" data-bs-toggle="collapse"
                                        href="#collapse{{ $publication->id }}" role="button"
                                        aria-controls="collapse{{ $publication->id }}">
                                        Ver comentarios
                                    </a>
                                        
                                    <div class="collapse mt-4" id="collapse{{ $publication->id }}">
                                        @foreach ($publication->comments as $comment)
                                            <div class="nombre d-flex flex-row">
                                                <div class="com_image">
                                                    <div class="card-photo rounded-circle " style="width: 40px; height:40px;">
                                                        @if ( $comment->user->image == null)
                                                            
                                                            <a style="color: inherit;"  href="{{ route('profile.view', ['prof' => $comment->user_id]) }}"> 
                                                                <p
                                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                                                </p>
                                                            </a> 
                                                        @else
                                                            <a style="color: inherit;"  href="{{ route('profile.view', ['prof' => $comment->user_id]) }}"> 
                                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                                    src="{{ $comment->user->image }}">
                                                            </a> 
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="com_content">
                                                    <h6 class="ml-4">
                                                        {{ $comment->user->name . ' ' . $comment->user->lastname }}
                                                    </h6>
                                                    <p class="ml-4 public-text">
                                                        {{ $comment->content }}
                                                    </p>
                                                </div> 
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="com" >
                                        <div class="card card-body" style="padding-bottom: 0;">
                                            <form method="POST" action="{{ route('comment') }}"
                                                class="comment">
                                                @csrf
                                                <input name="id_publication" id="id_publication" type="hidden"
                                                    value="{{ $publication->id }}">
                                                <div class="form-group row ">
                                                    <div class="col-md-12 align-content-center m-0 p-0">
                                                        <textarea id="content" name="content"
                                                            class="form-control @error('content') is-invalid @enderror"
                                                            placeholder="Escribe tu comentario..."></textarea>
                                                        @error('content')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="d-flex justify-content-end pr-0">
                                                        <button type="submit" class="boton">
                                                            Comentar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
           

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
     rgba(28, 98, 197, 0.8)),
     url('http://www.trademarket.com.mx/assets/imgs/quienes.jpg');
  	
	background-size: cover;
  	background-position: top;
  	position: relative;
    z-index: 1;
    
  	clip-path: polygon(10 0, 100% 0, 100% 100vh, 0 100%);
}

.container-image {
    width:200px; 
    height:200px;
    background: #ffffff; 
    margin-top: 19%;
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
    margin-top: -22%;
    overflow: hidden;
    z-index: 20;
}

.btnCreate{
    opacity: 0.7;
}

.profile-picture{
    width: 100%;
    height: 100%;
    object-fit: cover;
}


.container-text{
    margin-top: 22%;
    margin-left: 50px;
}

.container-text >*{
   color: #ffffff;
}

.box{
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
            border-radius: 10px;
}


</style>
@stop
