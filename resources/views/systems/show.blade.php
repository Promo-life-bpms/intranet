@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('systems.request')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Detalles del Usuario</h1> 

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="success">
                        {{session('success')}}
                    </div>   
                    @endif
            </div>
        </div>
    </div>
    
<!--body-->
    <div class="imagen-centrada">
    <img src="{{asset('img/profileChat.png')}}" alt="imagen" class="avatar">
    </div>
        <h5 class="title mt-3">{{$see_more->user->name.' '. $see_more->user->lastname}}</h5>
            <p class="description">
                Categoría: {{$see_more->category}} <br>
                Descripción: {{$see_more->description}}<br>
                Estado de solicitud: {{$see_more->status}}<br>
                Id de solicitud: {{$see_more->id}} <br>
                Fecha y hora de solicitud: {{$see_more->updated_at}} <br>
                Comentarios: {{$see_more->comments}}<br>
            </p>
                
            <form action="{{route('systems.store')}}" method="POST">
                @csrf
                <input type="text" value="{{$see_more->id}}" name="id" hidden>
                        <div class="button-container">
                            <textarea name="comments" id="comment"></textarea>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <div>
                                            
                                            </div>
                                        </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                                {!! Form::select('status', ['Aceptado'=> 'Aceptado', 'Rechazado'=> 'Rechazado'], 'Estado', ['class' => 'form-control','placeholder' => 'Seleccione el estado']) !!}
                                                @error('status')
                                                <small>
                                                    <font color="red"> *Este campo es requerido* </font>
                                                </small> 
                                                @enderror
                                        </div>
                                </div>
                        </div>
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
            </form> 
                        {!! Form::close()!!}
</div>
        
<style>
    img{
        width: 100px;
    }
.imagen-centrada {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px; /* Ajusta la altura del elemento según tus necesidades */
}
.imagen-centrada img {
    max-width: 100%;
    max-height: 100%;
}
    h1{
        text-align: 10%;
    }
</style>
@endsection