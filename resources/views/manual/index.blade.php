@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Manuales informativos</h3>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="container d-flex flex-wrap">
                <div class="card text-dark bg-light mb-3" style="width: 200px; height:240px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Reglamento Interno</h5>
                        <a href="{{ asset('/files/reglamento.pdf') }}" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                     </div>
                </div>
            
                <div class="card text-dark bg-light mb-3" style="width: 200px; height:240px; margin-left:20px;">
                    <img src="https://cdn.pixabay.com/photo/2017/03/08/21/20/pdf-2127829_960_720.png"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center" >Código de conducta y ética</h5>
                        <a href="{{ asset('/files/conducta.pdf') }}" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@stop
