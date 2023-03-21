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
    @if (session('error'))
        <div class="alert alert-info">
            {{ session('error') }}
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
           
            <div class="row" style="width:90%">
                <div class="col">
                    <input type="radio" name="document" value="determined_contract" id="determined_contract"> <label for="cbox2">Contrato determinado</label>  
                </div>
                <div class="col" id="form-checked-value" style="display:none">
                    <div class="input-group"style="margin-top:-10px">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Duración</span>
                    </div>
                    <input id="input_contrato_determinado" type="number" name="determined_contract_duration" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="Meses">
                    </div>
                </div>
            </div>
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
        <div class="col-md-5 cont2">
            <h6>Seleccionar Empresa</h6>
            <input type="radio" name="company" value="1" @if($postulant->company_id == 1)checked @endif>
            <label for="promolife">Promo life S de RL de CV</label><br>
            <input type="radio" name="company" value="2" @if($postulant->company_id == 2)checked @endif>
            <label for="bhtrademarket">BH Tade Market SA de CV</label><br>
            <input type="radio" name="company" value="3"  @if($postulant->company_id == 3)checked @endif>
            <label for="promozale">Promo Zale SA de CV</label><br>
            <input type="radio" name="company" value="4" @if($postulant->company_id == 4)checked @endif>
            <label for="trademarket57">Trade Market 57 SA de CV</label><br>
            <input type="radio" name="company" value="5" @if($postulant->company_id == 5)checked @endif>
            <label for="unipromtex">Unipromtex SA de CV</label><br>
        </div>
    </div>

    <br>
    {!! Form::submit('GENERAR DOCUMENTOS', ['class' => 'btnCreate mt-4']) !!}
    {!! Form::close() !!}


</div>
@stop

@section('scripts')
    <script>

        var determined_contract = document.getElementById("determined_contract");
        determined_contract.addEventListener("click", statusChange);

        function statusChange(event) {
            const currentValue = event.target.value;

            if(determined_contract.value == 'determined_contract'){
                document.getElementById('form-checked-value').style.display = 'block';
            }else{
                document.getElementById('form-checked-value').style.display = 'none';
            } 
        }
    </script>
@endsection