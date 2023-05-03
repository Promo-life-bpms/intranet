@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('team.record')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Mis Detalles</h1>
            </div>
        </div>
    </div>
</div>

<div class="imagen-centrada">
    <img src="{{asset('img/profileChat.png')}}" alt="imagen" class="avatar">
</div>
    <h5 class="title mt-3">{{$see_details->user->name.' '. $see_details->user->lastname}}</h5>
        <p class="description">
            Categoría: {{$see_details->category}}<br>
            Descripción: {{$see_details->description}}<br>
            Estado de solicitud: {{$see_details->status}}<br>
            Id de solicitud: {{$see_details->id}}<br>
            Fecha y hora de solicitud: {{$see_details->updated_at}}<br>
            Comentarios: {{$see_details->comments}}<br>
        </p>

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
