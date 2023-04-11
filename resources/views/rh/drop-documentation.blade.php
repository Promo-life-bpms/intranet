@extends('layouts.app')

@section('content')
<div class="card-header">
    <div class="d-flex flex-row">
        <a href="{{ route('rh.dropUser') }}">
            <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
        </a>
        <h3 class="separator">Generar baja</h3>
    </div>
</div>
<div class="card-body">
    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
    @endif
    <div class="container">

        <h5>Fecha de baja</h5>

        {!! Form::open(['route' => 'rh.buildDownDocumentation', 'enctype' => 'multipart/form-data']) !!}
      
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
            
            <div class="col">
                {!! Form::text('user_id', $user->id,['class' => 'form-control', 'hidden']) !!}

                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', $user->name,['class' => 'form-control', 'readonly']) !!}
            </div>
            <div class="col">
                {!! Form::label('lastname', 'Apellidos') !!}
                {!! Form::text('lastname', $user->lastname,['class' => 'form-control', 'readonly' ]) !!}
            </div>
            <div class="col">
                {!! Form::label('date_down', 'Fecha de baja') !!}
                {!! Form::date('date_down', null, ['class' => 'form-control'  ]) !!}
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
    
    @if ($user->userDetails != null)
        @if ($user->userDetails->date_down != null)
        <div>
            <form class="form-delete"
                action="{{ route('rh.dropDeleteUser', ['user' => $user->id]) }}"
                method="POST">
                @csrf
                @method('delete')
                <button style="width: 100%; height:50px;" type="submit" class="btn btn-outline-danger">GENERAR BAJA</button>
            </form> 
        </div>
        @endif
    @endif
   
          
 
@stop

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
     $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El usuario será dado de baja!",
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

@section('styles')

<style>
    label {
        margin-top: 10px;
    }

    .subtitle {
        margin-top: 30px;
    }

    hr {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .cont {
        margin-left: 24px;
        margin-right: 24px;
    }

    .cont2 {
        margin-bottom: 24px;
    }

    .separator {
        margin-left: 16px;
    }

    .arrouw-back {
        color: #1A346B;
    }
</style>
@endsection