@extends('layouts.app')
@section('content')
    <div class="card-header">

        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('admin.users.edit', ['user' => $id]) }}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
                </a>
                <h3 style="margin-left:16px;" class="separator">Documentación</h3> 
            </div>
                        
            <div class="d-flex">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalAdd"><i class="bi bi-plus-lg"></i>Agregar documento</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <br>
        @if($status == 1)
        <h5>Generar documentos</h5>

            {!! Form::open(['route' => 'rh.createUserDocument', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('user_id',$id,['class' => 'form-control', 'hidden']) !!}
                <br>
                @if(count($user_details)  == 0)
                    <div class="alert alert-light" role="alert">
                        No es posible generar documentación del empleado hasta llenar su <b>Información adicional</b>. 
                    </div>
                @else
                
                    <div class="alert alert-secondary" role="alert">
                        <div class="d-flex justify-content-between">    
                            <p class="mt-2">  CONTRATO INDETERMINADO  </p>             
                            <input type="submit" class="btn btn-primary" value="Descargar">
                        </div> 
                    </div>
                                
                @endif

            {!! Form::close() !!}

            <br>
            <br>
        @endif
        
        <h5>Documentos guardados</h5>
        @if(count($user_documents)  == 0)
            <div class="alert alert-light" role="alert">
                    Aún no hay documentos del usuario guardados, puedes subirlos dando clic al botón <b>Agregar documento</b>.
            </div>               
        @endif
        <div class ="row row-cols-2 row-cols-lg-4 g-2 g-lg-3" >

       
        @foreach ($user_documents as $document)
            <div class="col">
                <div class="card card_document">
                    
                    @switch($document->type)
                        @case('pdf' )
                        <iframe src="{{ asset($document->resource)}}" style="width:100%; height:150px;" frameborder="0"></iframe>
                        @break;
                        @case('png' )
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{ asset($document->resource)}}">
                        @break;
                        @case('jpg' )
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{ asset($document->resource)}}">
                        @break;
                        @case('jpeg' )
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{ asset($document->resource)}}">
                        @break;
                        @case('docx')
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{asset('img/Word.png')}}">
                        @break;
                        @case('doc')
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{asset('img/Word.png')}}">
                        @break;
                        @case('xlsx')
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{asset('img/RExcel.png')}}">
                        @break;
                        @case('xls')
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{asset('img/RExcel.png')}}">
                        @break;
                        @default
                        <img style="width:100%; height:150px; object-fit:contain;" src="{{asset('img/Documentos.png')}}">
                        @break;
                    @endswitch
                    <br>
                    
                    <p class="card-text">{{ strtoupper($document->description) }}</p>
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
                        {!! Form::open(['route' => 'rh.updateDocuments', 'enctype' => 'multipart/form-data', 'method'=>'POST']) !!}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="mb-2 form-group">                                    
                                        {!! Form::text('id', $document->id,['class'=>'form-control', 'hidden']) !!}
                                        {!! Form::label('id', 'Descripción') !!}
                                        {!! Form::text('description', $document->description, ['class' => 'form-control']) !!}
                                        @error('description')
                                        <small>
                                            <font color="red">*Este campo es requerido*</font>
                                        </small>
                                        <br>
                                        @enderror
                                    </div>
                                    <div class="mb-2 form-group">
                                    {!! Form::label('id', 'Archivo') !!}
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
                                {!! Form::label('description_text', 'Descripción', ['class'=>'required']) !!}
                                {!! Form::text('description', null,['class' => 'form-control']) !!}
                                @error('description')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                                @enderror
                            </div>
                            <br>

                            <div class="mb-2 form-group">
                                {!! Form::label('documents_text', 'Archivo', ['class'=>'required']) !!}
                                {!! Form::file('documents', ['class' => 'form-control', 'id' => 'input-file']) !!}
                                @error('documents')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                                @enderror
                            </div>
                            <br>
                            <div class="document-file" style="height:320px;">
                                <iframe src="" style="width:100%; height:100%;" frameborder="0" id="frame-file"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                
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

    <script>
        var inputFile = document.getElementById('input-file');
        var frameFile = document.getElementById('frame-file');
        inputFile.onchange = event => {
        const file = inputFile.files
        const [resource] = inputFile.files
        var extension = file[0].name.substr(file.length - 4);
            if (extension == 'pdf' || extension == 'png' || extension == 'jpg' || extension == 'peg') {
                frameFile.src = URL.createObjectURL(resource)
            }
            else{
                frameFile.src = ''
            }
            
        }
        
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
            height: 320px;
            object-fit: contain;
        }
        .required:after {
            content:" *";
            color: red;
        }
    </style>
@endsection