@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex flex-row">
        <a href="{{ route('rh.postulants') }}">
            <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
        </a>
        <h3 style="margin-left:16px;" class="separator">Generar documentación</h3>
    </div>
</div>
<div class="card-body">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <h5>{{ $postulant->name . ' '. $postulant->lastname}}</h5>
    <br>
    <div class="row">
        
        <div class="col cont2">
            
            <h6>Altas</h6>
            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('up_personal', "up_personal",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  ALTA PERSONAL  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
            
            <br>
            <h6>Contratos</h6> 

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('determined_contract', "determined_contract",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONTRATO DETERMINADO  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            
            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('indetermined_contract', "indetermined_contract",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONTRATO INDETERMINADO  </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
           
            <br>
            <h6>Convenios</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('confidentiality_agreement', "confidentiality_agreement",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONVENIO DE CONFIDENCIALIDAD </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('no_compete_agreement', "no_compete_agreement",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONVENIO DE NO COMPETENCIA </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            <br>
            <h6>Constrancias</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('work_condition_update', "work_condition_update",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2">  CONSTANCIA DE ACTUALIZACIÓN DE CONDICIONES DE TRABAJO </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}

            <br> 
            <h6>Cartas</h6>

            {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
                {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
                {!! Form::text('letter_for_bank', "letter_for_bank",['class' => 'form-control', 'hidden']) !!}

                <div class="alert alert-secondary" role="alert">
                    <div class="d-flex justify-content-between">    
                        <p class="mt-2"> CARTA PARA BANCO </p>             
                        <input type="submit" class="btn btn-primary" value="Descargar">
                    </div> 
                </div>
            {!! Form::close() !!}
        </div>
        
    </div>

    <br><br>

    @if (!session('message'))
        <div>
            <form class="form-convert"
                action="{{ route('rh.convertToEmployee', ['postulant_id' => $postulant->id]) }}"
                method="POST">
                @csrf
                @method('post')
                <button type="submit" style="height:48px" class="btn btn-success w-100 ">ASCENDER A EMPLEADO</button>
            </form>
        </div>
    @endif

</div>
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
    <script>
        $('.form-convert').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El candidato cambiará a empleado!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, migrar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@endsection