@extends('layouts.app')

@section('content')
<div class="card-header">

    <div class="d-flex justify-content-between">
        <div class="d-flex flex-row">
            <a href="{{ route('rh.dropUpdateDocumentation', ['id' => $user->id]) }}">
                <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
            </a>
            <h3 class="separator">Dar de baja a colaborador</h3>
        </div>
                        
        <div>                
           
        </div>
        </div>
</div>
<div class="card-body">
    <div class="progress" style="height: 25px;">
        <div class="progress-bar" role="progressbar" style="width: 33%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">  PASO 1 - Fecha y motivos de baja</div>
        <div class="progress-bar w-33" role="progressbar " style="width: 33%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> PASO 2 - Subir documentación</div>
        <div class="progress-bar w-33" role="progressbar " style="width: 33%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> PASO 3 - Baja de colaborador</div>
    </div>
    <br>
    <br>
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
                {!! Form::date('date_down', isset($user->userDetails->date_down) ? $user->userDetails->date_down : null , ['class' => 'form-control' , 'readonly'  ]) !!}
            </div> 
    </div>

    <br>
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
                <button style="width: 100%; height:50px;" type="submit" class="btn btn-danger">GENERAR BAJA</button>
            </form> 
        </div>
        @else
            <div class="alert alert-light" role="alert">
                Para poder dar de baja a este colaborador, debes llenar la <b>fecha de baja</b> descrito en el <b>PASO 1</b>.
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