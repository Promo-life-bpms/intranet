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
            Estado de solicitud: {{$user->status}} <br>
            Id de solicitud: {{$user->id}} <br>
            Fecha y hora de solicitud: {{$user->updated_at}} <br>
        </p>
            
        <form method="POST" action="">
            @csrf
                    <div class="button-container">
                        <button style="height: 36.9px" class="btn btn-sm btn-primary">Actualizar</button> 
                        &nbsp;
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width:190px">Seleccione una opción</button>
                        &nbsp;
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"></a>Aceptar</li>
                            <li><a class="dropdown-item" href="#"></a>Rechazar</li>
                        </ul>
                        <button type="button" class="btn btn-success">Enviar comentarios</button>
                    </div>
                    <br>
                    <textarea name="comment" id="comment"></textarea>
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