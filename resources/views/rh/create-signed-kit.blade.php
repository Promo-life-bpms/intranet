@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.createWorkplan', ['postulant_id' => $postulant->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a> 
            <h3 class="separator ms-2">Kit Legal Firmado</h3>
        </div>
                        
        <div>                
            <form 
                action="{{ route('rh.createUpPostulant', ['postulant_id' => $postulant->id]) }}"
                method="GET">
                 @csrf
                <button type="submit" class="btn btn-primary"> 
                    Alta de Colaborador
                    <i class="fa fa-arrow-right" aria-hidden="true"></i> 
                </button>
            </form>
        </div>
    </div>
</div>
<div class="card-body">

    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 16.6%;">  
                    <a href="#step-1" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">1</a>
                    <p><small>Alta de Candidato</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-2" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">2</a>
                    <p><small>Recepción de Documentos</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-3" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
                    <p><small>Kit legal de Ingreso</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle  no-selected" disabled="disabled">4</a>
                    <p><small>Plan de Trabajo</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle" disabled="disabled">5</a>
                    <p><small>Kit Legal Firmado</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 16.6%;"> 
                    <a href="#step-4" type="button" class="btn btn-default btn-circle  no-selected" disabled="disabled">6</a>
                    <p><small>Alta de Colaborador</small></p>
                </div>
            </div>
        </div>
    </div>

    <br>
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
            <br>
        </div>
    @endif

  <!--   <div class="alert alert-light" role="alert">
        Sube tus documentos firmados en esta sección, estos pueden ser modificados una vez que el candidato sea dado de alta.
        <br>
        <b>Nota:</b> Si deseas subir un documento en especifico o conjunto de documentos, puedes subirlos y guardarlos, no afectara al resto de documentos subidos.
    </div>
 -->
 
    <div class="alert alert-light" role="alert">
        Sube aqui tus documentos firmados, tras finalizar podrás dar de <b>Alta al Colaborador</b> en la sistema.<br>
        <b>Kit Legal: </b> Contrato (requerido), Convenio de confidencialidad (requerido). <br>
        <b>Documentos Personales: </b>CV, Acta de nacimiento, CURP, INE, NSS, Comprobante de domicilio, Comprobante de estudios, Certificado médico, Cuenta de banco, Constancia de situación fiscal.
    </div>
    <br>

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <h5 style="margin-left:16px;" class="separator">Kit de Contratación</h5> 
        </div>
                    
        <div class="d-flex">
            <button type="button" class="btn btn-success m-1" data-bs-toggle="modal"  data-bs-target="#modalAdd"><i class="bi bi-plus-lg"></i>Agregar documento</button>
        </div>
    </div>

  
    @if (count($kit) == 0  && count($personal) == 0   )
        <div class="alert alert-light" role="alert">
            Aún no hay documentos del usuario guardados, puedes subirlos dando clic al botón <b>Agregar documento</b>.
        </div>    
    @endif
            
    <div class ="row row-cols-2 row-cols-lg-4 g-2 g-lg-3" >   
        @foreach ($kit as $document)

            @if ($document->description == 'contact' || $document->description == 'confidentiality')
                <div class="col">
                    <div class="card card_document">

                        @if ($document->type == 'pdf')
                            <iframe src="{{ asset($document->resource)}}" style="width:100%; height:100%;" frameborder="0"></iframe>
                        @else
                            <div style="width: 100%; height: 150px; text-align: center;">
                            Vista previa no disponible
                            </div> 
                        @endif                        
                        <br>
                        <p class="card-text">
                        @switch($document->description)
                            @case('contact')
                                CONTRATO
                                @break

                            @case('confidentiality')
                                CONVENIO DE CONFIDENCIALIDAD
                                @break
                        
                            @default
                                
                        @endswitch
                        </p>
                        <a href="{{asset($document->resource)}}" style="width: 100%" target="_blank" class="btn btn-primary btn-sm">Abrir</a><br>
                        <div class="d-flex w-100" >
                            <form class="delete-postulant w-100" action="{{ route('rh.deletePostulantDocuments', ['document_id' =>$document->id, 'postulant_id' => $postulant->id]) }}" method="POST">
                                @csrf
                                @method('post')
                                <input type="submit" class="btn btn-danger btn-sm w-100" value="Eliminar archivo">
                                    
                                </input>
                            </form>
                        </div>                
                    </div>
                </div>
            @endif
            
        @endforeach
    </div>

    
    <h5>Documentos personales</h5>

    <div class ="row row-cols-2 row-cols-lg-4 g-2 g-lg-3" >   
        @foreach ($personal as $document)
        
            <div class="col">
                <div class="card card_document">
                    @if ($document->type == 'pdf')
                        <iframe src="{{ asset($document->resource)}}" style="width:100%; height:100%;" frameborder="0"></iframe>
                    @else
                        Vista previa no disponible
                    @endif
                    
                    <br>
                    <p class="card-text">
                    @switch($document->description)
                     
                        @case('birth_certificate')
                            ACTA DE NACIMIENTO
                            @break
                 

                        @case('domicile')
                            COMPROBANTE DE DOMICILIO
                            @break
                    
                    
                        @case('study_centificate')
                            CERTIFICADO DE ESTUDIOS
                            @break

                        @case('medic_centificate')
                            CERTIFICADO MÉDICO
                            @break
                    
                        @case('bank_account')
                            CUENTA DE BANCO
                            @break
                      
                        @case('fiscal_centificate')
                            CONSTANCIA DE SITUACION FISCAL
                            @break
                    
                        @default
                        {{ strtoupper($document->description)}}
                    @endswitch
                    </p>
                    <a href="{{asset($document->resource)}}" style="width: 100%" target="_blank" class="btn btn-primary btn-sm">Abrir</a><br>
                    <div class="d-flex w-100" >
                        <form class="delete-postulant w-100" action="{{ route('rh.deletePostulantDocuments', ['document_id' =>$document->id, 'postulant_id' => $postulant->id]) }}" method="POST">
                            @csrf
                            @method('post')
                            <input type="submit" class="btn btn-danger btn-sm w-100" value="Eliminar archivo">
                                
                            </input>
                        </form>
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
                {!! Form::open(['route' => 'rh.storePostulantDocuments', 'enctype' => 'multipart/form-data']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-2 form-group">
                                {!! Form::text('postulant_id', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                                <b>Documento</b>
                                {!! Form::select('description', [ 'contact' => 'Contrato','confidentiality' => 'Convenio de confidencialidad', 'cv' => 'CV', 'birth_certificate' => 'Acta de Nacimiento','curp' => 'CURP', 'ine' => 'INE','nss' => 'NSS','domicile' => 'Domicilio','study_centificate' => 'Comprobante de estudios','medic_centificate' => 'Certificado médico','bank_account' => 'Cuenta de banco','fiscal_centificate' => 'Constancia de situacion fiscal'],null,['class' => 'form-control']) !!}
                                <br>
                                
                            </div>

                            <div class="mb-2 form-group">
                                <b>Plan de Trabajo</b>
                                {!! Form::file('document', ['class' => 'form-control','id'=>'input-file']) !!}
                                @error('document')
                                <small>
                                    <font color="red">*Este campo es requerido*</font>
                                </small>
                                <br>
                                @enderror
                            </div>

                            <br>
                            <iframe src="" style="width:100%; height:100%;" frameborder="0" id="frame-file"></iframe>

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

@section('styles')
<style>
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

    .modal-body{
        height: 580px;
    }
</style>
@endsection

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <script>
        $('.delete-postulant').submit(function(e) {
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
                    console.log('yaaaaaaaaa');
                    this.submit();
                }
            })
        });

        function wrongAlert() {
            Swal.fire('No disponible hasta subir documento de "Plan de trabajo"');
        }
    </script>

    <script>
        var inputFile = document.getElementById('input-file');
        var frameFile = document.getElementById('frame-file');
        inputFile.onchange = event => {
        const file = inputFile.files
        const [resource] = inputFile.files
        var extension = file[0].name.substr(file.length - 4);
            if (extension == 'pdf') {
                console.log('Es PDF')
                frameFile.src = URL.createObjectURL(resource)
            }
            console.log(file[0].name)
            
        }

        
    </script>
@endsection