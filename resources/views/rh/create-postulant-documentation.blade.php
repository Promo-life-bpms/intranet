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

    {!! Form::open(['route' => 'rh.buildPostulantDocumentation', 'enctype' => 'multipart/form-data']) !!}
    <h5>{{ $postulant->name . ' '. $postulant->lastname}}</h5>
    <br>
    <div class="row">
        
        <div class="col-md-5 cont2">
            {!! Form::text('postulant', $postulant->id,['class' => 'form-control', 'hidden']) !!}
            <h6>Alta</h6>
            <input type="radio" name="document" value="up_personal" checked> <label for="cbox2">Alta personal</label>
            <br><br>
            <h6>Contratos</h6> 
           
            <input type="radio" name="document" value="determined_contract" id="determined_contract"> <label for="cbox2">Contrato determinado</label>  
            <br>
            <input type="radio" name="document" value="indetermined_contract"> <label for="cbox2">Contrato indeterminado</label> 
            
            <br><br>
            <h6>Convenios</h6>
            <input type="radio" name="document"  value="confidentiality_agreement"> <label for="cbox2">Convenio de confidencialidad</label>
            <br>
            <input type="radio" name="document"  value="no_compete_agreement"> <label for=" cbox2">Convenio de no competencia</label>
            <br>
            <br>
            <h6>Constrancias</h6>
            <input type="radio" name="document"  value="work_condition_update"> <label for="cbox2">Constancia de actualización de condiciones de trabajo</label>
            <br> <br>
            <h6>Cartas</h6>
            <input type="radio" name="document"  value="letter_for_bank" > <label for="cbox2">Carta para banco</label>
        </div>
        
    </div>

    <br>
    {!! Form::submit('GENERAR DOCUMENTOS', ['class' => 'btnCreate mt-4']) !!}
    {!! Form::close() !!}

    <br>

    @if (!session('message'))
        <div>
            <form class="form-convert"
                action="{{ route('rh.convertToEmployee', ['postulant_id' => $postulant->id]) }}"
                method="POST">
                @csrf
                @method('post')
                <button type="submit" style="height:48px" class="btn btn-outline-primary w-100 ">CONVERTIR A EMPLEADO</button>
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