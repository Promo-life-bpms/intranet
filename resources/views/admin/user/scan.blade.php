@extends('layouts.app')
@section('content')

<?php
    $style='display:flex;flex-direction:column;justify-content:space-between;box-shadow: 0px 1px 10px rgba(0, 0, 0,0.2);margin:13px;padding:15px;  height:20pv;';
?>
    <div class="card-header">
        <h3>Documentos escaneados</h3>
    </div>
    <div class="card-body">
        @if(session('message'))
        <div class="alert alert-succes">
            {{session('message')}}
        </div>
        @endif
        <div class="d-flex justify-content-between">
            <h4>Documentos de baja</h4>
            <div class="d-flex">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalImage1"><i class="bi bi-plus-lg"></i>Agregar documento</button>
            </div>
        </div>
        
        <div class ="row row-cols-1 row-cols-md-4 g-4" style="margin-top:20px;">
        @foreach ($user_documents as $document)
        <article id="otros" class="sombra" style=<?php echo $style ?>>
            <div class="card h-100">
                @switch($document)
                @case($document->type=='docx')
                <img src="{{asset('img/Word.png')}}">
                @break;

                @case($document->type=='xlsx')
                <img src="{{asset('img/RExcel.png')}}">
                @break;

                @case ($document->type=='pdf')
                <img src="{{asset('img/pdf.png')}}">
                @break;

                @default
                <img src="{{asset('img/Documentos.png')}}">
                @endswitch

                <p class="card-text">{{$document->description}}</p>
                <a href="{{asset($document->resource)}}" style="width: 100%" target="_blank" class="btn btn-primary btn-sm">Abrir</a><br>
                <div class="d-grid gap-2 d-md-block" >
                    
                    <form class="form-delete m-2 mt-0" action="{{ route('rh.deleteDocuments', ['document_id' =>$document->id]) }}" method="POST">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                        <button class="btn btn-success btn-sm"  type="button" data-bs-toggle="modal" data-bs-target="#modalEditar"><i class="fa fa-pencil" aria-hidden="true"></i></button>  
                    </form>
                </div>
            </div>   
        </article>
        
        <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                     <div class="modal-header">
                        <h5 class="modal-title" id="modalImageLabel">Seleccione el archivo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    {!! Form::open(['route' => 'rh.updateDocuments', 'enctype' => 'multipart/form-data', 'method'=>'put']) !!}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="mb-2 form-group">
                                    {!! Form::text('document_id', $document->id,['class' => 'form-control', 'hidden']) !!}
                                    <p>Descripción</p>
                                    {!! Form::text('description', $document->description,['class' => 'form-control']) !!}
                                </div>
                                <div class="mb-2 form-group">
                                {!! Form::file('documents', ['class' => 'form-control']) !!}
                            </div>
                            @error('documents')
                            <small>
                                <font color="red">*Este campo es requerido*</font>
                            </small>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Aceptar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="modal fade" id="modalImage1" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImageLabel">Seleccione el archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'rh.storeDocuments', 'enctype' => 'multipart/form-data']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                        <div class="mb-2 form-group">
                            {!! Form::text('user_id', $id,['class' => 'form-control', 'hidden']) !!}
                            <p>Descripción</p>
                            {!! Form::text('description', null,['class' => 'form-control']) !!}
                        </div>
                        <div class="mb-2 form-group">
                                {!! Form::file('documents', ['class' => 'form-control']) !!}
                        </div>
                            @error('documents')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Aceptar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @stop

    @section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

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
@endsection


    @section ('styles')
    <style>
    .card {
        margin:20px;
        border-radius: 6px;  
        overflow: hidden;
        background: #fff;          
        cursor:default;
        transition: all 400 ms ease;
    }
    .sombra{
        height: 50vh;
        width: 30vh;
        margin:13px;
        border-radius: 6px;
        box-shadow: 5px 5px 20px rgba(0, 0, 0,0.4);
        cursor: default;
        transform: translateY(-3%);
    }
    #otros{
        display: contents;
    }
    .card img{
        width: 100%;
        height:210px;
    }
    .card .card-text{
        padding:10px;
        text-align:center;
    }
    </style>
    @endsection