@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex flex-row" >
            <a  href="{{ route('rh.dropUser') }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i> 
            </a>
            <h3 class="separator">Documentación de baja</h3> 
        </div>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="row gx-5 ">
            <div class="row g-0">
                <div class="col-md-4 cont" >
                
                <h5>Generar documentacion de baja</h5>
                    <br>
                    {!! Form::open(['route' => 'rh.buildDownDocumentation', 'enctype' => 'multipart/form-data']) !!}
                        <h6>Datos del empleado</h6>
                    
                        {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}  

                        {!! Form::label('name', 'Nombre') !!}
                        {!! Form::text('name', $user->name,['class' => 'form-control', 'readonly']) !!}     

                        {!! Form::label('lastname', 'Apellidos') !!}
                        {!! Form::text('lastname', $user->lastname,['class' => 'form-control', 'readonly' ]) !!}   
                            
                        {!! Form::label('department_id', 'Departamento') !!}
                        {!! Form::select('department_id', $departments, $user->employee->position->department_id, ['class' => 'form-control', 'readonly' ]) !!}

                        <h6 class="subtitle">Empresa seleccionada para generar documentos de baja</h6>
                        {!! Form::label('company_id', 'Empresa') !!}
                        {!! Form::select('company_id', $companies, $user->employee->companies[0]->id, ['class' => 'form-control']) !!}

                        <br>
                        {!! Form::submit('GENERAR DOCUMENTO DE BAJA', ['class' => 'btnCreate mt-4']) !!}
                    {!! Form::close() !!}
                    </div> 
                    
                   
                    <!--  Formulario de motivo -->
                    @if(count( $user_down_motive) ==0)
                       
                        <div class="col-md-5 cont">
                            <h5>Motivos de baja</h5>
                            <br>
                            {!! Form::open(['route' => 'rh.createMotiveDown', 'enctype' => 'multipart/form-data']) !!}
                            <div class="row g-0">
                                <div class="col-md-5 cont2" >
                                    {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}  
                                    <h6>Crecimiento laboral</h6>
                                    <input class="check" type="checkbox" name="growth_salary" value="true"> <label for="cbox2">Sueldo</label>
                                    <br>
                                    <input class="check" type="checkbox" name="growth_promotion" value="true"> <label for="cbox2">Ascensos</label>
                                    <br>
                                    <input class="check" type="checkbox" name="growth_activity" value="true"> <label for="cbox2">Actividades desempeñadas</label>
                                </div>
                                <div class="col-md-5 cont2" >
                                    <h6>Clima laboral</h6>
                                    <input class="check" type="checkbox" name="climate_partnet" value="true"> <label for="cbox2">Compañeros de trabajo</label>
                                    <br>
                                    <input class="check" type="checkbox" name="climate_manager" value="true"> <label for="cbox2">Jefe directo</label>
                                    <br>
                                    <input class="check" type="checkbox" name="climate_boss" value="true"> <label for="cbox2">Directivos</label>
                                </div>
                                <br>
                                <div class="col-md-5 cont2" >
                                    <h6>Factores de riesgo psicosocial</h6>
                                    <input class="check" type="checkbox" name="psicosocial_workloads" value="true"> <label for="cbox2">Cargas de trabajo excesivas</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_appreciation" value="true"> <label for="cbox2">Falta de reconocimiento</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_violence" value="true"> <label for="cbox2">Violencia laboral</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_workday" value="true"> <label for="cbox2">Jornadas laborales</label>
                                </div>
                                <div class="col-md-5 cont2" >
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
                                <div class="col-md-5 cont2" >
                                    <h6>Salud</h6>
                                    <input class="check" type="checkbox" name="health_personal" value="true"> <label for="cbox2">Personal</label>
                                    <br>
                                    <input class="check" type="checkbox" name="health_familiar" value="true"> <label for="cbox2">Familiar</label>
                                </div> 

                            </div>

                            <br>
                            {!! Form::submit('GUARDAR MOTIVO DE BAJA', ['class' => 'btnCreate mt-4']) !!}
                            {!! Form::close() !!}
                    
                        </div>    
                        </div>
                    @else
                        @foreach($user_down_motive as $motive)
                            <div class="col-md-1"></div>

                        <div class="col-md-6 cont">
                            <h5>Motivos de baja</h5>
                            <br>
                            {!! Form::open(['route' => 'rh.createMotiveDown', 'enctype' => 'multipart/form-data']) !!}
                            <div class="row g-0">
                                <div class="col-md-5 cont2" >
                                    {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}  
                                    <h6>Crecimiento laboral</h6>
                                        <input class="check" type="checkbox" name="growth_salary" value="true"  @if($motive->growth_salary <> null)checked @endif> <label for="cbox2">Sueldo</label>   
                                        <br>
                                        <input class="check" type="checkbox" name="growth_promotion" value="true"  @if($motive->growth_promotion <> null)checked @endif> <label for="cbox2">Ascensos</label>
                                        <br>
                                        <input class="check" type="checkbox" name="growth_activity" value="true" @if($motive->growth_activity <> null)checked @endif> <label for="cbox2">Actividades desempeñadas</label>
                                </div>
                                <div class="col-md-5 cont2" >
                                    <h6>Clima laboral</h6>
                                    <input class="check" type="checkbox" name="climate_partnet" value="true" @if($motive->climate_partnet <> null)checked @endif> <label for="cbox2">Compañeros de trabajo</label>
                                    <br>
                                    <input class="check" type="checkbox" name="climate_manager" value="true" @if($motive->climate_manager <> null)checked @endif> <label for="cbox2">Jefe directo</label>
                                    <br>
                                    <input class="check" type="checkbox" name="climate_boss" value="true" @if($motive->climate_boss <> null)checked @endif> <label for="cbox2">Directivos</label>
                                </div>
                                <br>
                                <div class="col-md-5 cont2" >
                                    <h6>Factores de riesgo psicosocial</h6>
                                    <input class="check" type="checkbox" name="psicosocial_workloads" value="true" @if($motive->psicosocial_workloads <> null)checked @endif> <label for="cbox2">Cargas de trabajo excesivas</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_appreciation" value="true" @if($motive->psicosocial_appreciation <> null)checked @endif> <label for="cbox2">Falta de reconocimiento</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_violence" value="true" @if($motive->psicosocial_violence <> null)checked @endif> <label for="cbox2">Violencia laboral</label>
                                    <br>
                                    <input class="check" type="checkbox" name="psicosocial_workday" value="true" @if($motive->psicosocial_workday <> null)checked @endif> <label for="cbox2">Jornadas laborales</label>
                                </div>
                                <div class="col-md-5 cont2" >
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
                                <div class="col-md-5 cont2" >
                                    <h6>Salud</h6>
                                    <input class="check" type="checkbox" name="health_personal" value="true" @if($motive->health_personal <> null)checked @endif> <label for="cbox2">Personal</label>
                                    <br>
                                    <input class="check" type="checkbox" name="health_familiar" value="true" @if($motive->health_familiar <> null)checked @endif> <label for="cbox2">Familiar</label>
                                </div> 
                            </div>

                            <br>
                            {!! Form::submit('GUARDAR MOTIVO DE BAJA', ['class' => 'btnCreate mt-4']) !!}
                            {!! Form::close() !!}
                    
                        </div>    
                    </div>
                        @endforeach
                    @endif
                    
                    
                    
            </div>
        </div>
    </div>
@stop

@section('styles')

<style>
    label{
       margin-top: 10px;
    }

    .subtitle{
        margin-top: 30px;
    }

    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .cont{
        margin-left: 24px;
        margin-right: 24px;
    }

    .cont2{
        margin-bottom: 24px;
    }

    .separator{
        margin-left: 16px;
    }

    .arrouw-back{
        color: #1A346B;
    }
</style>
@endsection