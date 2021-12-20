@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <h3>Accesos</h3>
        <a href="{{ route('admin.noworkingdays.create') }} " type="button" class="btn btn-success">Agregar</a>
    </div>
</div>
  
    <div class="card-body m-2">
        <div class="d-flex flex-wrap">

            @foreach ($access as $acc)
            <div class="content" style="" >
                <div class="card m-1" >
                    <div class="card-header p-2" >
                        <h4 class="text-center" > {{$acc->title}}</h4>
                        </div>
                    <div class="card-body" >
                        <img style="width: 100%; max-height:120px;  object-fit: contain;" src="{{$acc->image}}">
                    
                        <a style="width: 100%; justify-content:center;" href="" type="button"
                        class="btn btn-outline-dark d-flex align-items-center mt-2 mb-2">INGRESAR</a>

                        <div class="d-flex justify-content-center">
                            <a style="width: 100%" href="" type="button"  class="btn btn-outline-success m-2">VER</a>
                            <form class="form-delete"
                                action="" method="POST" style="width: 100%">
                                @csrf
                                @method('delete')
                                <button style="width: 95%" type="submit" class="btn btn-outline-danger m-2">BORRAR</button>
                            </form>
                        </div>
                    </div>
                </div>     
            </div>
                
            @endforeach
        </div>
    </div>
@stop
