@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <div class="d-flex flex-row">
                <a  href="{{ route('team.admon')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Detalles de Solicitud</h1>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="success">
                        {{session('success')}}
                    </div>   
                    @endif
            </div>
    </div>

    <div class="row">
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Estado</h5>
                    <p class="description" style="font-size: 15px;">
                        @if ($information_request->status == 1)
                                    <div class="text-left">
                                        <span class="badge bg-success">Aprobada</span>
                                    </div>
    
                                    @elseif($information_request->status == 2)
                                    <div class="text-left">
                                        <span class="badge bg-danger">Rechazada</span>
                                    </div>
    
                                    @elseif($information_request->status == 0)
                                    <div class="text-left">
                                        <span class="badge bg-info text-dark">Solicitud Creada</span>
                                    </div>
                        @endif
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Fecha de Solicitud Creada</h5>
                    <p class="description" style="font-size: 15px;">
                        Fecha de solicitud creada: {{$information_request->created_at}}<br>
                    </p>
            </div>
                
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Datos Generales del Personal de Nuevo Ingreso</h5>
                    <p class="description" style="font-size: 15px;">
                        id de Solicitud: {{$information_request->id}}<br>
                        Tipo de usuario: {{$information_request->type_of_user}}<br>
                        Nombre: {{$information_request->user->name.' '. $information_request->user->lastname}}<br>
                        Fecha requerida: {{$information_request->date_admission}}<br>
                        Área: {{$information_request->area}}<br>
                        Departamento: {{$information_request->departament}}<br>
                        Puesto: {{$information_request->position}}<br>
                        Extensión: {{$information_request->extension}}<br>
                        Jefe inmediato: {{$information_request->immediate_boss}}<br>
                        Empresa: {{$information_request->company}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Asignación de Equipo de Cómputo y Telefonía</h5>
                    <p class="description" style="font-size: 15px;">
                        Tipo de computadora: {{$information_request->computer_type}}<br>
                        Celular: {{$information_request->cell_phone}}<br>
                        #: {{$information_request->number}}<br>
                        No. de extensión: {{$information_request->extension_number}}<br>
                        Equipo a utilizar: {{$information_request->equipment_to_use}}<br>
                        Accesorios: {{$information_request->accessories}}<br>
                        En caso de ser reasignación de equipo indique el usuario anterior: {{$information_request->previous_user}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Cuenta(s) de Correo(s) Requerida(s)</h5>
                    <p class="description" style="font-size: 15px;">

                        @foreach ($datos=json_decode($information_request->email, true)  as $index => $elemento )
                            @if($elemento !== null)
                                Correo:{{$index + 1}}: {{$elemento}}<br>
                            @endif
                        @endforeach

                    </p>
            </div>
            
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Firma: Número(s) de Contacto Telefónico</h5>
                    <p class="description" style="font-size: 15px;">

                        @foreach ($datos=json_decode($information_request->signature_or_telephone_contact_numer, true) as $index => $elemento)
                            @if ($elemento!==null)
                                Firma:{{$index + 1}}: {{$elemento}}<br>
                            @endif
                        @endforeach
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h5>
                    <p class="description" style="font-size: 15px;">
                        Distribución y Reenvíos: {{$information_request->distribution_and_forwarding}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Software Requerido</h5>
                    <p class="description" style="font-size: 15px;">
                        Office: {{$information_request->office}}<br>
                        Acrobat PDF: {{$information_request->acrobat_pdf}}<br>
                        PhotoShop: {{$information_request->photoshop}}<br>
                        Premier: {{$information_request->premier}}<br>
                        Audition: {{$information_request->audition}}<br>
                        Solid Works: {{$information_request->solid_works}}<br>
                        Autocad: {{$information_request->autocad}}<br>
                        ODOO: {{$information_request->odoo}}<br>
                        
                        {{-- @foreach ($perfiles=json_decode($information_request->work_profile_in_odoo, true) as $indexa => $profile)
                            @foreach ($usuarios = json_decode($information_request->odoo_users, true) as $index => $element)
                                @if ($index == $indexa)
                                    @if ($element !== null)
                                        Usuario(s) de ODOO: {{$index + 1}}: {{$element}}<br>
                                    @endif
                                        Perfil de Trabajo en ODOO: {{$index + 1}}: {{$profile}}<br>
                                @endif
                            @endforeach
                        @endforeach --}}

                        @foreach ($perfiles = json_decode($information_request->work_profile_in_odoo, true) as $indexa => $profile)
                            @foreach ($usuarios = json_decode($information_request->odoo_users, true) as $index => $element)
                                @if ($index == $indexa && ($element !== null || $profile !== null))
                                    @if ($element !== null)
                                        Usuario(s) de ODOO: {{$index + 1}}: {{$element}}<br>
                                    @endif
                                    @if ($profile !== null)
                                        Perfil de Trabajo en ODOO: {{$index + 1}}: {{$profile}}<br>
                                    @endif
                                @endif
                            @endforeach
                        @endforeach
                        
                        Otros: {{$information_request->others}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h5>
                    <p class="description" style="font-size: 15px;">
                        Requiere Acceso a Carpeta Compartida del Servidor: {{$information_request->access_to_server_shared_folder}}<br>
                        Ruta de la Carpeta: {{$information_request->folder_path}}<br>
                        Tipo de Acceso: {{$information_request->type_of_access}}<br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Observaciones</h5>
                    <p class="description" style="font-size: 15px;">
                        Observaciones: {{$information_request->observations}}<br>
                    </p>
            </div>
    </div>

    <form action="{{route('team.status')}}" method="POST">
        {!! Form::open(['route' => 'team.status', 'enctype' => 'multipart/form-data']) !!}
                @csrf
                <input type="text" value="{{$information_request->id}}" name="id" hidden>
                <div class="col-md-3">
                    <div class="form-group">
                            {!! Form::select('status', ['Aprobada'=> 'Aprobada', 'Rechazada'=> 'Rechazada'], 'Estado', ['class' => 'form-control','placeholder' => 'Seleccione el cambio de estado']) !!}
                            @error('status')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small> 
                            @enderror
                    </div>
                </div>

                {{-- @if($user->id === 6)
                    @if($information_request->status === 0)
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                    @elseif($information_request->status === 1)
                        @if($user->id === 31)
                            {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                        @else
                            {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4', 'disabled' => 'disabled']) !!}
                        @endif
                    @else
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4', 'disabled' => 'disabled']) !!}
                    @endif
                @elseif($user->id === 31)
                    @if($information_request->status === 1)
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                    @else
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4', 'disabled' => 'disabled']) !!}
                    @endif
                @else
                    {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4', 'disabled' => 'disabled']) !!}
                @endif --}}

                {{-- @if (($user->id === 6 || $user->id === 31 || $user->id === 127) && $information_request->status === 0)
                    {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                @elseif ($user->id === 31 && ($information_request->status === 1 || $enable_button_for_user_id_31))
                    @if ($information_request->status !== 2 && $information_request->status === 1 )
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                    @else
                        {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4', 'disabled' => 'disabled']) !!}
                    @endif
                @elseif ($user->id === 6 && $information_request->status === 2)
                    {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4']) !!}
                @else
                    {!! Form::submit('ACTUALIZAR', ['class' => 'btnCreate mt-4 btnDisabled', 'disabled' => 'disabled']) !!}
                @endif --}}
                
        {!! Form::close()!!}
    </form>
</div>

<style>
    h1{
        text-align: 10%;
    }

    .container {
    display: flex;
}

.left-column,
.right-column {
    flex: 1;
}

.left-column {
    margin-right: 10px;
}

.right-column {
    margin-left: 10px;
}

.btnDisabled {
    opacity: 0.5;
    pointer-events: none;
}
</style>
@endsection