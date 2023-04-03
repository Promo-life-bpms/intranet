@extends('layouts.app')
@section('content')
    <div class="card-header">
        <h3>Documentos escaneados</h3>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <div class="d-flex justify-content-between">
        <h4>Documentos de baja</h4>
            <div class="d-flex">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalAdd"><i class="bi bi-plus-lg"></i>Agregar documento</button>
            </div>
        </div>

        <br>
        <div class ="row row-cols-2 row-cols-lg-4 g-2 g-lg-3" >
        @foreach ($user_documents as $document)
            <div class="col">
                <div class="card card_document">
                    @switch($document)
                    @case($document->type=='docx' ||$document->type=='doc' )
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
                    @break;
                    @endswitch
                    <p class="card-text">{{$document->description}}</p>
                    <a href="{{asset($document->resource)}}" style="width: 100%" target="_blank" class="btn btn-primary btn-sm">Abrir</a><br>
                    <div class="d-grid gap-2 d-md-block" >
                        <form class="form-delete m-2 mt-0" action="{{ route('rh.deleteDocuments', ['document_id' =>$document->id]) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                            <button class="btn btn-success btn-sm"  type="button" data-bs-toggle="modal" data-bs-target="#modalEditar{{$document->id}}"><i class="fa fa-pencil" aria-hidden="true"></i></button>  
                        </form>
                    </div>                
                </div>

            </div>
                    
            
            <div class="modal fade" id="modalEditar{{$document->id}}" tabindex="-1" aria-labelledby="modalEdit" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">                    
                            <h5 class="modal-title" id="modalEdit">Seleccione el archivo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        {!! Form::open(['route' => 'rh.updateDocuments', 'enctype' => 'multipart/form-data', 'method'=>'put']) !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2 form-group">                                    
                                        {!! Form::text('id', $document->id,['class'=>'form-control', 'hidden']) !!}
                                        <p>Descripción del archivo</p>
                                        {!! Form::text('description', $document->description, ['class' => 'form-control']) !!}
                                        @error('description')
                                        <small>
                                            <font color="red">*Este campo es requerido*</font>
                                        </small>
                                        <br>
                                        @enderror
                                    </div>
                                    <div class="mb-2 form-group">
                                        {!! Form::file('documents', ['class' => 'form-control']) !!}
                                        @error('documents')
                                        <small>
                                            <font color="red">*Este campo es requerido*</font>
                                        </small>
                                        @enderror
                                    </div>
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

    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
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
                                @error('description')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                                @enderror
                            </div>

                            <div class="mb-2 form-group">
                                {!! Form::file('documents', ['class' => 'form-control']) !!}
                                @error('documents')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                                @enderror
                            </div>
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
        .card_document{
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            border: 1px solid #000;
            display: flex;
            align-items: center;
            padding: 24px;   
        }

        .card_document>img{
            width: 160px;
            height: 160px;
            object-fit: contain;
        }
        
    </style>
@endsection