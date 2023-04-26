@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <h1 style="font-size:20px">Detalles del Usuario</h1>
    
<!--body-->
    <div class="imagen-centrada">
    <img src="{{asset('img/profileChat.png')}}" alt="imagen" class="avatar">
    </div>
        <h5 class="title mt-3">{{$user->user->name.' '. $user->user->lastname}}</h5>
            <p class="description">
                Categoría: {{$user->category}} <br>
                Descripción: {{$user->description}}<br>
                Estado de solicitud: {{$user->status}}<br>
                Id de solicitud: {{$user->id}} <br>
                Fecha y hora de solicitud: {{$user->updated_at}} <br>
                Comentarios: <br>
            </p>
                
            <form action="" method="POST">

                @csrf
                        <div class="button-container">
                            <textarea name="comment" id="comment"></textarea>   
                        </div>
                        <br>
                        
                        <div class="row">
                            <div class="col-md-4">
                                    <div class="form-group">
                                        <div>
                                            <a type="button" class="btn btn-success"  href="{{ route('systems.request')}}">Regresar</a>
                                        </div>
                                    </div>

                            <div class="col-md-6">
                                    <div class="form-group">
                                            {!! Form::select('status', ['Aceptar'=> 'Aceptar', 'Rechazar'=> 'Rechazar'], 'Estado', ['class' => 'form-control','placeholder' => 'Seleccione el estado']) !!}
                                    </div>
                                </div>
                            </div>
                                            {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                        </div>
            </form>       
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
</style>
@endsection