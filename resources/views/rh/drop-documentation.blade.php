@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.dropUser') }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a> 
            <h3 class="separator ms-2">Fecha y Motivos de Baja</h3>
        </div>
                        
        <div>                
            <form 
                action="{{ route('rh.dropUpdateDocumentation', ['id' => $user->id]) }}"
                method="GET">
                 @csrf
                <button type="submit" class="btn btn-primary"> 
                    Documentación
                    <i class="ms-2 fa fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="container" >
        <div class="stepwizard">
            <div class="stepwizard-row setup-panel">
                <div class="stepwizard-step col-xs-3" style="width: 33%;">  
                    <a href="#" type="button" class="btn btn-default btn-circle" disabled="disabled">1</a>
                    <p><small>Fecha y Motivos de Baja</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a href="{{ route('rh.dropUpdateDocumentation', ['id' => $user->id]) }}" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">2</a>
                    <p><small>Documentación</small></p>
                </div>
                <div class="stepwizard-step col-xs-3"  style="width: 33%;"> 
                    <a href="{{ route('rh.dropUserDetails', ['id' =>$user->id]) }}" type="button" class="btn btn-default btn-circle no-selected" disabled="disabled">3</a>
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
    <br>
    @endif

    <div class="alert alert-light" role="alert">
        Ingresa la <b>fecha de baja</b> y marca los <b> motivos de baja</b>.
    </div>

    <div class="container">

        <h5>Fecha de baja</h5>

        {!! Form::open(['route' => 'rh.buildDownDocumentation', 'enctype' => 'multipart/form-data']) !!}
      
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
            
            <div class="col form-group">
                {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}

                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', $user->name,['class' => 'form-control', 'readonly']) !!}
            </div>
            <div class="col form-group">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::text('lastname', $user->lastname,['class' => 'form-control', 'readonly' ]) !!}
            </div>
            <div class="col form-group">
                {!! Form::label('date_down', 'Fecha de baja',['class'=>'required']) !!}
                {!! Form::date('date_down', isset($user->userDetails->date_down) ? $user->userDetails->date_down : null , ['class' => 'form-control'  ]) !!}
                @error('date_down')
                    <small>
                        <font color="red"> *Este campo es requerido* </font>
                    </small>
                    <br>
                @enderror
            </div> 
    </div>

    <br><br>
    <h5>Motivo de baja</h5>
    <br>
    <!--  Formulario de motivo -->
    @if(count( $user_down_motive) ==0)

        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
      
            <div class="col">
                {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}
                <h6>Crecimiento laboral</h6>
                <input class="check" type="checkbox" name="growth_salary" value="true"> <label for="cbox2">Sueldo</label>
                <br>
                <input class="check" type="checkbox" name="growth_promotion" value="true"> <label for="cbox2">Ascensos</label>
                <br>
                <input class="check" type="checkbox" name="growth_activity" value="true"> <label for="cbox2">Actividades desempeñadas</label>
            </div>
            <div class="col">
                <h6>Clima laboral</h6>
                <input class="check" type="checkbox" name="climate_partnet" value="true"> <label for="cbox2">Compañeros de trabajo</label>
                <br>
                <input class="check" type="checkbox" name="climate_manager" value="true"> <label for="cbox2">Jefe directo</label>
                <br>
                <input class="check" type="checkbox" name="climate_boss" value="true"> <label for="cbox2">Directivos</label>
            </div>
            <br>
            <div class="col">
                <h6>Factores de riesgo psicosocial</h6>
                <input class="check" type="checkbox" name="psicosocial_workloads" value="true"> <label for="cbox2">Cargas de trabajo excesivas</label>
                <br>
                <input class="check" type="checkbox" name="psicosocial_appreciation" value="true"> <label for="cbox2">Falta de reconocimiento</label>
                <br>
                <input class="check" type="checkbox" name="psicosocial_violence" value="true"> <label for="cbox2">Violencia laboral</label>
                <br>
                <input class="check" type="checkbox" name="psicosocial_workday" value="true"> <label for="cbox2">Jornadas laborales</label>
            </div>
            <div class="col">
                <h6>Factores demograficos</h6>
                <input class="check" type="checkbox" name="demographics_distance" value="true"> <label for="cbox2">Distancia</label>
                <br>
                <input class="check" type="checkbox" name="demographics_physical" value="true"> <label for="cbox2">Riesgos fisicos</label>
                <br>
                <input class="check" type="checkbox" name="demographics_personal" value="true"> <label for="cbox2">Actividades personales</label>
                <br>
                <input class="check" type="checkbox" name="demographics_school" value="true"> <label for="cbox2">Actividades escolares</label>
            </div>
            <br>
            <div class="col">
                <h6>Salud</h6>
                <input class="check" type="checkbox" name="health_personal" value="true"> <label for="cbox2">Personal</label>
                <br>
                <input class="check" type="checkbox" name="health_familiar" value="true"> <label for="cbox2">Familiar</label>
            </div>
            <div class="col">
                <h6>Otro</h6>
                <input type="text" class="form-control" name="other_motive" maxlength="255">
            </div>
            <br>

        </div>

    @else
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
            @foreach($user_down_motive as $motive)
    
            <div class="col">
                {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}
                <h6>Crecimiento laboral</h6>
                <input class="check" type="checkbox" name="growth_salary" value="true" @if($motive->growth_salary <> null)checked @endif> <label for="cbox2">Sueldo</label>
                    <br>
                    <input class="check" type="checkbox" name="growth_promotion" value="true" @if($motive->growth_promotion <> null)checked @endif> <label for="cbox2">Ascensos</label>
                        <br>
                        <input class="check" type="checkbox" name="growth_activity" value="true" @if($motive->growth_activity <> null)checked @endif> <label for="cbox2">Actividades desempeñadas</label>
            </div>
            <div class="col">
                <h6>Clima laboral</h6>
                <input class="check" type="checkbox" name="climate_partnet" value="true" @if($motive->climate_partnet <> null)checked @endif> <label for="cbox2">Compañeros de trabajo</label>
                    <br>
                    <input class="check" type="checkbox" name="climate_manager" value="true" @if($motive->climate_manager <> null)checked @endif> <label for="cbox2">Jefe directo</label>
                        <br>
                        <input class="check" type="checkbox" name="climate_boss" value="true" @if($motive->climate_boss <> null)checked @endif> <label for="cbox2">Directivos</label>
            </div>
            <br>
            <div class="col">
                <h6>Factores de riesgo psicosocial</h6>
                <input class="check" type="checkbox" name="psicosocial_workloads" value="true" @if($motive->psicosocial_workloads <> null)checked @endif> <label for="cbox2">Cargas de trabajo excesivas</label>
                    <br>
                    <input class="check" type="checkbox" name="psicosocial_appreciation" value="true" @if($motive->psicosocial_appreciation <> null)checked @endif> <label for="cbox2">Falta de reconocimiento</label>
                        <br>
                        <input class="check" type="checkbox" name="psicosocial_violence" value="true" @if($motive->psicosocial_violence <> null)checked @endif> <label for="cbox2">Violencia laboral</label>
                            <br>
                            <input class="check" type="checkbox" name="psicosocial_workday" value="true" @if($motive->psicosocial_workday <> null)checked @endif> <label for="cbox2">Jornadas laborales</label>
            </div>
            <div class="col">
                <h6>Factores demograficos</h6>
                <input class="check" type="checkbox" name="demographics_distance" value="true" @if($motive->demographics_distance <> null)checked @endif> <label for="cbox2">Distancia</label>
                    <br>
                    <input class="check" type="checkbox" name="demographics_physical" value="true" @if($motive->demographics_physical <> null)checked @endif> <label for="cbox2">Riesgos fisicos</label>
                        <br>
                        <input class="check" type="checkbox" name="demographics_personal" value="true" @if($motive->demographics_personal <> null)checked @endif> <label for="cbox2">Actividades personales</label>
                            <br>
                            <input class="check" type="checkbox" name="demographics_school" value="true" @if($motive->demographics_school <> null)checked @endif> <label for="cbox2">Actividades escolares</label>
            </div>
            <br>
            <div class="col">
                <h6>Salud</h6>
                <input class="check" type="checkbox" name="health_personal" value="true" @if($motive->health_personal <> null)checked @endif> <label for="cbox2">Personal</label>
                    <br>
                    <input class="check" type="checkbox" name="health_familiar" value="true" @if($motive->health_familiar <> null)checked @endif> <label for="cbox2">Familiar</label>
            </div>

            <div class="col">
                <h6>Otro</h6>
                <input type="text" class="form-control" name="other_motive" maxlength="255" @if($motive->other_motive <> null) value= {{ $motive->other_motive}} @endif>
            </div>
            <br>

            @endforeach
        </div>
    @endif

    {!! Form::submit('GUARDAR DOCUMENTACION DE BAJA', ['class' => 'btnCreate mt-4']) !!}
    {!! Form::close() !!}

    <br>

 
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
        .required:after {
            content:" *";
            color: red;
        }

   </style>
@endsection
