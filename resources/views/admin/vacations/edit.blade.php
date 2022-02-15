@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Editar Vacaciones</h3>
            <button class="btn btn-success" onclick="sumar()">Calcular DV</button>
        </div>
    </div>
    <div class="card-body">

        {!! Form::model($vacation, ['route' => ['admin.vacations.update', $vacation], 'method' => 'put']) !!} 

        <div class="row">
           
            <div class="col-md-6">
                <p >Empleado</p>
                <input type="text" id="name" name="name" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos" readonly
                value="{{ $vacation->user->name.' '.$vacation->user->lastname }}">
            </div>

            <div class="col-md-6">
                <p >Fecha de ingreso</p>
                <input type="text" id="date_admission" name="date_admission" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos" readonly
                value="{{ $vacation->user->employee->date_admission }}">
            </div>
        </div>

        <br>

        <div class="row">

            <div class="col-md-4">
                <p >Dias de periodos cumplidos</p>
                <input type="number" id="period_days" name="period_days" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->period_days }}">
            </div> 

            <div class="col-md-4">
                <p >Dias Actuales</p>
                <input type="number" id="current_days" name="current_days" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->current_days }}">
            </div> 

            <div class="col-md-4">
                <p >D.V.</p>
                <input type="number" name="dv" id="dv" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->dv }}">

            </div> 

        </div>
        {!! Form::submit('ACTUALIZAR VACACIONES', ['class' => 'btnCreate mt-4']) !!}

        
        {!! Form::close() !!}

    </div>

@stop


@section('styles')
    <style>
        #name>* {
            font-size: 20px;

        }

    </style>
@stop



@section('scripts')
<script>
function sumar(){
    
    var period_days = document.getElementById('period_days').value;
    var current_days = document.getElementById('current_days').value;
    var suma = parseFloat(period_days).toFixed(2) - parseFloat(current_days).toFixed(2);
    var dv = document.getElementById('dv');

    dv.value = parseInt(suma.toFixed(2)) 
  
    
}
</script>

@stop
