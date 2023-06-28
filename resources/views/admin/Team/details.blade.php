@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-row">
                <a  href="{{ route('team.record')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Mis Detalles</h1>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Estado</h5>
            <p class="description" style="font-size: 15px;">
                @if ($see_details->status == 'Aprobada')
                            <div class="text-left">
                                <span class="badge bg-success">{{$see_details->status}}</span>
                            </div>

                            @elseif($see_details->status == 'Rechazada')
                            <div class="text-left">
                                <span class="badge bg-danger">{{ $see_details->status }}</span>
                            </div>

                            @elseif($see_details->status == 'Preaprobada')
                            <div class="text-left">
                                <span class="badge bg-warning text-dark">{{ $see_details->status }}</span>
                            </div>

                            @elseif($see_details->status == 'Solicitud Creada')
                            <div class="text-left">
                                <span class="badge bg-info text-dark">{{ $see_details->status }}</span>
                            </div>
                @endif
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Fecha de Solicitud Creada</h5>
            <p class="description" style="font-size: 15px;">
                Fecha de solicitud creada: {{$see_details->created_at}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Datos Generales del Personal de Nuevo Ingreso</h5>
            <p class="description" style="font-size: 15px;">
                id de Solicitud: {{$see_details->id}}<br>
                Tipo de usuario: {{$see_details->type_of_user}}<br>
                Nombre: {{$see_details->user->name.' '. $see_details->user->lastname}}<br>
                Fecha requerida: {{$see_details->date_admission}}<br>
                Área: {{$see_details->area}}<br>
                Departamento: {{$see_details->departament}}<br>
                Puesto: {{$see_details->position}}<br>
                Extensión: {{$see_details->extension}}<br>
                Jefe inmediato: {{$see_details->immediate_boss}}<br>
                Empresa: {{$see_details->company}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Asignación de Equipo de Cómputo y Telefonía</h5>
            <p class="description" style="font-size: 15px;">
                Tipo de computadora: {{$see_details->computer_type}}<br>
                Celular: {{$see_details->cell_phone}}<br>
                #: {{$see_details->number}}<br>
                No. de extensión: {{$see_details->extension_number}}<br>
                Equipo a utilizar: {{$see_details->equipment_to_use}}<br>
                Accesorios: {{$see_details->accessories}}<br>
                En caso de ser reasignación de equipo indique el usuario anterior: {{$see_details->previous_user}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Cuenta(s) de Correo(s) Requerida(s)</h5>
            <p class="description" style="font-size: 15px;">

                @foreach ($datos=json_decode($see_details->email, true)  as $index => $elemento )
                    @if($elemento !== null)
                        Correo:{{$index + 1}}: {{$elemento}}<br>
                    @endif
                @endforeach
            </p>
    </div>
    
    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Firma: Número(s) de Contacto Telefónico</h5>
            <p class="description" style="font-size: 15px;">

                @foreach ($datos=json_decode($see_details->signature_or_telephone_contact_numer, true) as $index => $elemento)
                    @if ($elemento!==null)
                        Firma:{{$index + 1}}: {{$elemento}}<br>
                    @endif
                @endforeach
    
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h5>
            <p class="description" style="font-size: 15px;">
                Distribución y Reenvíos: {{$see_details->distribution_and_forwarding}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Software Requerido</h5>
            <p class="description" style="font-size: 15px;">
                Office: {{$see_details->office}}<br>
                Acrobat PDF: {{$see_details->acrobat_pdf}}<br>
                PhotoShop: {{$see_details->photoshop}}<br>
                Premier: {{$see_details->premier}}<br>
                Audition: {{$see_details->audition}}<br>
                Solid Works: {{$see_details->solid_works}}<br>
                Autocad: {{$see_details->autocad}}<br>
                ODOO: {{$see_details->odoo}}<br>
                
                @foreach ($perfiles=json_decode($see_details->work_profile_in_odoo, true) as $indexa => $profile)
                    @foreach ($usuarios = json_decode($see_details->odoo_users, true) as $index => $element)
                        @if ($index == $indexa)
                            @if ($element !== null)
                                Usuario(s) de ODOO: {{$index + 1}}: {{$element}}<br>
                            @endif
                                Perfil de Trabajo en ODOO: {{$index + 1}}: {{$profile}}<br>
                        @endif
                    @endforeach
                @endforeach

                Otros: {{$see_details->others}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h5>
            <p class="description" style="font-size: 15px;">
                Requiere Acceso a Carpeta Compartida del Servidor: {{$see_details->access_to_server_shared_folder}}<br>
                Ruta de la Carpeta: {{$see_details->folder_path}}<br>
                Tipo de Acceso: {{$see_details->type_of_access}}<br>
            </p>
    </div>

    <div class="col-md-6">
        <h5 class="title mt-3" style="font-size: 15px;">Observaciones</h5>
            <p class="description" style="font-size: 15px;">
                Observaciones: {{$see_details->observations}}<br>
            </p>
    </div>
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
    </style>
    @endsection
