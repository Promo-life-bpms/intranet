@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <h3>Accesos</h3>
        <a href="{{ route('access.create') }} " type="button" class="btn btn-success">Agregar</a>
    </div>
</div>
  
    <div class="card-body m-2">
      
            <div class="row">
                <div class="container d-flex justify-content-around">
                    <div class="card text-dark bg-light " style=" width:240px; height:220px;">
                        <img src="https://h2m8x3y5.rocketcdn.me/wp-content/uploads/2020/03/ODOO-LOGO.png"
                            style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <h5 class="card-title text-center">ODOO</h5>
                            <a href="#" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                         </div>
                    </div>
                
                    <div class="card text-dark bg-light " style="width:240px; height:220px;">                        <img src="https://res.cloudinary.com/dte7upwcr/image/upload/blog/blog2/como-hacer-una-encuesta-en-google-forms/como-hacer-una-encuesta-en-google-forms-img_header.jpg"
                            style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <h5 class="card-title text-center" >Evaluación 360</h5>
                            <a href="#" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                        </div>
                    </div>
                    
                    <div class="card text-dark bg-light " style="width:240px; height:220px;">                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQXqlbOw2PNu8YEGN7uixN9ucllojqJ4fQK9g&usqp=CAU"
                            style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <h5 class="card-title text-center" >Cotizador Web</h5>
                            <a href="#" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                        </div>
                    </div>
                    
                    <div class="card text-dark bg-light " style="width:240px; height:220px;">                        <img src="data:image/png;base64,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"
                            style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <h5 class="card-title text-center" >Help Desk</h5>
                            <a href="#" style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                        </div>
                    </div>
                    

                </div>
            </div>

            <div class="row">
                
                <div class="container d-flex justify-content-around">
              
                    @foreach ($access as $acc)
                        <div class="card text-dark bg-light m-1 "  >
                            <div class="card-header text-dark bg-light p-2" >
                                <h4 class="text-center" > {{$acc->title}}</h4>
                                </div>
                            <div class="card-body" >
                                <img style="width: 100%; max-height:120px;  object-fit: contain;" src="{{$acc->image}}">
                            
                                <a style="width: 100%; justify-content:center;" target="_blank" href="{{$acc->link}}" type="button"
                                class="btn btn-primary d-flex align-items-center mt-2 mb-2">INGRESAR</a>
        
                                <div class="d-flex justify-content-center">
                                    <a style="width: 80px"  href="{{ route('access.edit', ['acc' => $acc->id]) }}" type="button" class="btn btn-outline-success m-2">VER</a>
                                    <form class="form-delete"
                                        action="{{ route('access.delete', ['acc' => $acc->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button style="width: 90px" type="submit" class="btn btn-outline-danger m-2">BORRAR</button>
                                    </form>
                                </div>
                            </div>
                        </div>     
                    @endforeach
                </div>

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
