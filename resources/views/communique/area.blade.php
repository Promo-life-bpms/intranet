@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Comunicados de área/departamento</h3>
    </div>
    <div class="card-body">
        <div class="row">
        </div>
        @foreach ($departmentCommuniques as $company)
        @if ($company->file==null)
            @if ($company->image==null)
            <div class="card w-100 bg-light mb-3">
                <div class="card-body">
                <h5 class="card-title">{{$company->title}}</h5>
                <p class="card-text">{{$company->description}}</p>
                </div>
            </div>
            @else
                <div class="card w-100 bg-light mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <img class="card-img-top" style="object-fit: contain; max-height:500px;" src="{{ asset($company->image )}}" alt="Card image cap">
                            </div>

                            <div class="col-6">
                        
                                <h5 class="card-title">{{$company->title}}</h5>
                                <p class="card-text">{{$company->description}}</p>
                            
                            </div>
                        </div>
                   
                        
                    </div>
                </div>

            @endif

        @else 
            @if ($company->image==null)
                <div class="card w-100 bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{$company->title}}</h5>
                        <p class="card-text">{{$company->description}}</p>
                        <a class="btn btn-primary"  href="{{ asset($company->file )}}" target="_blank">Abrir archivo adjunto</a>
                    </div>
                </div>

            @else 
            <div class="card w-100 bg-light mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <img class="card-img-top" style="object-fit: contain; max-height:500px;" src="{{ asset($company->image )}}" alt="Card image cap">
                        </div>

                        <div class="col-6">
                    
                            <h5 class="card-title">{{$company->title}}</h5>
                            <p class="card-text">{{$company->description}}</p>
                            <a class="btn btn-primary"  href="{{ asset($company->file )}}" target="_blank">Abrir archivo adjunto</a>

                        </div>
                    </div>
               
                    
                </div>
            </div>

            @endif
        @endif
            
        @endforeach
    </div>
@stop


@section('styles')
@stop
