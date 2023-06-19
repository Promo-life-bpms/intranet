@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <div class="d-flex flex-row">
                <a  href="{{ route('team.admon')}}">
                    <i class="fa fa-arrow-left fa-2x arrouw-back" aria-hidden="true"></i>
                </a>
                <h1  style="margin-left:16px; font-size:25px" class="separator">Detalles de Solicitud</h1>
            </div>

    <div class="row">
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Datos Generales del Personal de Nuevo Ingreso</h5>
                    <p class="description" style="font-size: 15px;">
                        Tipo de usuario: <br>
                        Nombre: <br>
                        Fecha requerida: <br>
                        Área: <br>
                        Departamento: <br>
                        Puesto: <br>
                        Extensión: <br>
                        Jefe inmediato <br>
                        Empresa: <br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Asignación de Equipo de Cómputo y Telefonía</h5>
                    <p class="description" style="font-size: 15px;">
                        Tipo de computadora: <br>
                        Celular: <br>
                        #: <br>
                        No. de extensión: <br>
                        Equipo a utilizar: <br>
                        Accesorios: <br>
                        En caso de ser reasignación de equipo indique el usuario anterior: <br>
                        </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Cuenta(s) de Correo(s) Requerida(s)</h5>
                    <p class="description" style="font-size: 15px;">
                        Correo: <br>
                    </p>
            </div>
            
            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Firma: Número(s) de Contacto Telefónico</h5>
                    <p class="description" style="font-size: 15px;">
                        Firma: <br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Lista de Distribución y Reenvíos: (todos@ están considerados por default)</h5>
                    <p class="description" style="font-size: 15px;">
                        Distribución y Reenvíos: <br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Software Requerido</h5>
                    <p class="description" style="font-size: 15px;">
                        Office: <br>
                        Premier: <br>
                        Autocad: <br>
                        Acrobat PDF: <br>
                        Audition: <br>
                        ODOO: <br>
                        PhotoShop: <br>
                        Solid Works: <br>
                        Usuario(s) de ODOO: <br>
                        Perfil de Trabajo en ODOO <br>
                        Otros: <br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Carpetas Compartidas del Servidor a las que debe tener acceso</h5>
                    <p class="description" style="font-size: 15px;">
                        Requiere Acceso a Carpeta Compartida del Servidor: <br>
                        Ruta de la Carpeta: <br>
                        Tipo de Acceso: <br>
                    </p>
            </div>

            <div class="col-md-6">
                <h5 class="title mt-3" style="font-size: 15px;">Observaciones</h5>
                    <p class="description" style="font-size: 15px;">
                        Observaciones: <br>
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