@extends('layouts.app')
@section('content')
    <div class="card-header">

        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('rh.dropDocumentation', ['user' => $id]) }}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
                </a>
                <h3 style="margin-left:16px;" class="separator">Documentación</h3> 
            </div>
            <div class="d-flex flex-row">
                
                <div class="align-self-center">
                    <form 
                        action="{{ route('rh.dropUserDetails', ['id' => $id]) }}"
                        method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary"> 
                            Baja de Colaborador
                            <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>     
            </div> 
        </div>
    </div>
    <div class="card-body">

    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 33%;">  
                    <a href="{{ route('rh.dropDocumentation', ['user' => $id]) }}" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">1</a>
                    <p><small>Fecha y Motivos de Baja</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a href="#" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                    <p><small>Documentación</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a href="{{ route('rh.dropUserDetails', ['id' => $id]) }}" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
                    <p><small>Baja de Colaborador</small></p>
                </div>
            </div>
        </div>
    </div>

    <br> 
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif      
    
        <div class="d-flex justify-content-between">
            <h5>Documentos guardados</h5>
            <div class="align-self-center">
                <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalAdd"><i class="bi bi-plus-lg"></i>Agregar documento</button>
            </div>
        </div>

        <br>
   
        @if(count($user_documents)  == 0)
            <div class="alert alert-light" role="alert">
                    Aún no hay documentos del usuario guardados, puedes subirlos dando clic al botón <b>Agregar documento</b>.
            </div>               
        @endif

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
                                        {!! Form::label('name', 'Nombre del archivo') !!}

                                        {!! Form::text('description', $document->description, ['class' => 'form-control']) !!}
                                        
                                    </div>
                                    <div class="mb-2 form-group">
                                        {!! Form::label('name', 'Actualizar archivo (opcional)') !!}

                                        {!! Form::file('documents', ['class' => 'form-control']) !!}
                                        
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
                              
                            </div>

                            <div class="mb-2 form-group">
                                {!! Form::file('documents', ['class' => 'form-control']) !!}
                             
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
    <br>
    <p>En caso de no contar con la documentación necesaria al momento de realizar la baja del colaborador, puedes agregarla despues en la sección de <b> Usuarios dados de baja .</b></p>

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

        .text-info{
            display: none;
        }
        .fa-info-circle{
            margin-left: 8px;
            color: #1A346B;
        }

        .fa-info-circle:hover {
            margin-left: 8px;
            color: #0084C3;
        }
      
        #icon-text {
            display: none;
            margin-left: 16px;
            color: #fff;
            background-color: #1A346B;
            padding: 0 12px 0 12px;
            border-radius: 10px;
            font-size: 14px;
        }

        #content:hover~#icon-text{
            display: block;
        }

        .stepwizard-step p {
            margin-top: 0px;
            color:#666;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .btn-default{
            background-color: #0084C3;
        }

        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content:" ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #fff;
        }

        .no-selected{
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
            color: #000;
            background-color: #fff;
            border-color: #0084C3;
        }

        
    </style>
@endsection

