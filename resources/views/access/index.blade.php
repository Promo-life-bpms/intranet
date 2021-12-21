@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <h3>Accesos</h3>
        <a href="{{ route('access.create') }} " type="button" class="btn btn-success">Agregar</a>
    </div>
</div>
  
    <div class="card-body m-2">
        <div class="d-flex flex-wrap">

            @foreach ($access as $acc)
            <div class="content" style="background: #ffffff; width:280px;" >
                <div class="card m-1 "style="border: 1px solid #002235;"  >
                    <div class="card-header p-2" >
                        <h4 class="text-center" > {{$acc->title}}</h4>
                        </div>
                    <div class="card-body" >
                        <img style="width: 100%; max-height:120px;  object-fit: contain;" src="{{$acc->image}}">
                    
                        <a style="width: 100%; justify-content:center;" href="{{$acc->link}}" type="button"
                        class="btn btn-primary d-flex align-items-center mt-2 mb-2">INGRESAR</a>

                        <div class="d-flex justify-content-center">
                            <a style="width: 80px"  href="{{ route('access.edit', ['acc' => $acc->id]) }}" type="button"  class="btn btn-outline-success m-2">VER</a>
                            <form class="form-delete"
                                action="{{ route('access.delete', ['acc' => $acc->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button style="width: 90px" type="submit" class="btn btn-outline-danger m-2">BORRAR</button>
                            </form>
                        </div>
                    </div>
                </div>     
            </div>
                
            @endforeach
        </div>
    </div>
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El registro se eliminará permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@stop
