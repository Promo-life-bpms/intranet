@extends('layouts.app')

@section('content')
    @if (session('message'))
        <div id="alert-succ" class="alert alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="26" height="26"
                stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div id="alert-err" class="alert alert-danger">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="26" height="26"
                stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:">
                <use xlink:href="#exclamation-triangle-fill" />
                <path
                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </svg>
            {{ session('warning') }}
        </div>
    @endif

    <div>
        <div id="loadingSpinner"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(255, 255, 255, 0.8); z-index:9999; justify-content:center; align-items:center;">
            <div class="spinner-border text-primary" role="status">
            </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h3 id="titlePage">Solicitudes autorizadas</h3>


            <div id="exporTable">
                {!! Form::open(['route' => 'request.export', 'id' => 'exportForm']) !!}
                <button id="buttonExpor" type="submit" class="btn btn-success"
                    style="margin-left: 20px; background-color: #81C10C !important; border-color: #81C10C !important; ">Exportar</button>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-5 align-content-end">
                <div class="row">
                    <div class="col-4">
                        <input id="searchName" type="search" class="form-control" placeholder="Nombre">
                    </div>
                    <div class="col-5">
                        <select id="tipoSelect" name="tipo" class="form-select">
                            <option value="">Tipo de permiso</option>
                            <option value="Vacaciones" {{ request('tipo') == 'Vacaciones' ? 'selected' : '' }}>Vacaciones
                            </option>
                            <option value="Ausencia" {{ request('tipo') == 'Ausencia' ? 'selected' : '' }}>Ausencia</option>
                            <option value="Permisos especiales"
                                {{ request('tipo') == 'Permisos especiales' ? 'selected' : '' }}>Permisos especiales
                            </option>
                            <option value="Incapacidad" {{ request('tipo') == 'Incapacidad' ? 'selected' : '' }}>Incapacidad
                            </option>
                            <option value="Paternidad" {{ request('tipo') == 'Paternidad' ? 'selected' : '' }}>Paternidad
                            </option>
                        </select>
                    </div>


                    <div class="col-3 d-flex align-items-center justify-content-center">
                        <input id="fechaInput" type="date" class="form-control" value="{{ request('fecha') }}"
                            style="width: 2.8rem" />
                        <i style="margin-left: 0.5rem" id="clearFilter" class="fas fa-times-circle"
                            style="cursor: pointer;"></i>
                    </div>
                </div>
            </div>
            <div class="col-7">
                <div class="row">
                    <div class="col-4">
                        <div id="tarjeta1" class="tarjetaRh1 hover-tarjetaRh1"
                            style="border-bottom: 10px solid var(--color-target-1); min-height: 100%; padding: 10px 20px !important;">
                            <div class="d-flex justify-content-end">
                                <strong>{{ $sumaAprobadas }}</strong>
                            </div>
                            <div style="margin-top: 25px">
                                <strong>Autorizadas</strong>
                            </div>
                        </div>
                    </div>


                    <div class="col-4">
                        <div id="tarjeta2" class="tarjetaRh2 hover-tarjetaRh2"
                            style="border-bottom: 10px solid var(--color-target-3); min-height: 100%; padding: 10px 20px !important;">
                            <div class="d-flex justify-content-end">
                                <strong>{{ $sumaPendientes }}</strong>
                            </div>
                            <div style="margin-top: 25px">
                                <strong>Pendientes</strong>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div id="tarjeta3" class="tarjetaRh3 hover-tarjetaRh3"
                            style="border-bottom: 10px solid var(--color-target-4); min-height: 100%; padding: 10px 20px !important; align-content: end !important; ">
                            <div class="d-flex justify-content-end">
                                <strong>{{ $sumaCanceladasUsuario }}</strong>
                            </div>
                            <div>
                                <strong>Canceladas por el usuario</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="mt-3" style="min-width: 100% !important;">
                {{-- Tabla para autorizadas --}}
                <div id="tableAutorizadas">
                    <table class="table" style="min-width: 100% !important;">
                        <thead style="background-color: #072A3B; color: white;">
                            <tr>
                                <th scope="col" style="text-align: center;">#</th>
                                <th scope="col" style="text-align: center;">Solicitante</th>
                                <th scope="col" style="text-align: center;">Tipo de solicitud</th>
                                <th scope="col" style="text-align: center;">Días ausente</th>
                                <th scope="col" style="text-align: center;">Aprobado por (Jefe)</th>
                                <th scope="col" style="text-align: center;">Aprobado por (RH)</th>
                                <th scope="col" style="text-align: center;">Detalles</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($Aprobadas) == 0)
                                <tr>
                                    <td colspan="7" style="text-align: center;">No tienes solicitudes</td>
                                </tr>
                            @endif
                            @foreach ($Aprobadas as $aprovada)
                                <tr class="solicitud-row1" data-days1="{{ implode(',', $aprovada->days_absent) }}">
                                    <th style="text-align: center; align-content: center;" scope="row">
                                        {{ $aprovada->id }}</th>
                                    <td style="text-align: center;">{{ $aprovada->name }}</td>
                                    <td style="text-align: center;">{{ $aprovada->request_type }}</td>
                                    <td style="text-align: center;">
                                        @foreach ($aprovada->days_absent as $day)
                                            <div>
                                                {{ $day }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($aprovada->direct_manager_status == 'Pendiente')
                                            <span class="badge bg-warning text-dark">
                                                {{ $aprovada->direct_manager_status }}
                                            </span>
                                        @elseif ($aprovada->direct_manager_status == 'Aprobada')
                                            <span class="badge bg-success text-white">
                                                {{ $aprovada->direct_manager_status }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ $aprovada->direct_manager_status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($aprovada->rh_status == 'Pendiente')
                                            <span class="badge bg-warning text-dark">
                                                {{ $aprovada->rh_status }}
                                            </span>
                                        @elseif ($aprovada->rh_status == 'Aprobada')
                                            <span class="badge bg-success text-white">
                                                {{ $aprovada->rh_status }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ $aprovada->rh_status }}
                                            </span>
                                        @endif
                                    </td>

                                    <td style="text-align: center; cursor: pointer;">
                                        <button class="btn btn-link openModalDetails" data-id="{{ $aprovada->id }}"
                                            data-crete="{{ $aprovada->created_at }}" data-name="{{ $aprovada->name }}"
                                            data-image="{{ $aprovada->image }}"
                                            data-current_vacation="{{ $aprovada->current_vacation }}"
                                            data-current_vacation_expiration="{{ $aprovada->current_vacation_expiration }}"
                                            data-next_vacation="{{ $aprovada->next_vacation }}"
                                            data-expiration_of_next_vacation="{{ $aprovada->expiration_of_next_vacation }}"
                                            data-direct_manager_status="{{ $aprovada->direct_manager_status }}"
                                            data-rh_status="{{ $aprovada->rh_status }}"
                                            data-request_type="{{ $aprovada->request_type }}"
                                            data-specific_type="{{ $aprovada->specific_type }}"
                                            data-days_absent="{{ implode(',', $aprovada->days_absent) }}"
                                            data-timeArray="{{ is_Array($aprovada->time) ? 'true' : 'false' }}"
                                            data-start="{{ $aprovada->time ? $aprovada->time[0]['start'] : '12:00' }}"
                                            data-end="{{ $aprovada->time ? $aprovada->time[0]['end'] : '12:00' }}"
                                            {{-- Para Ausencia --}}
                                            data-value-type="{{ is_array($aprovada->more_information) && count($aprovada->more_information) > 0 && isset($aprovada->more_information[0]['value_type']) ? $aprovada->more_information[0]['value_type'] : '0' }}"
                                            data-tipo-de-ausencia="{{ is_array($aprovada->more_information) && isset($aprovada->more_information[0]['Tipo_de_ausencia']) ? $aprovada->more_information[0]['Tipo_de_ausencia'] : '-' }}"
                                            {{-- Para permisos especiales --}}
                                            data-tipo-permiso-especial="{{ is_array($aprovada->more_information) && count($aprovada->more_information) > 0 && isset($aprovada->more_information[0]['Tipo_de_permiso_especial']) ? $aprovada->more_information[0]['Tipo_de_permiso_especial'] : '-' }}"
                                            data-reveal_id="{{ $aprovada->reveal_id }}"
                                            data-file="{{ $aprovada->file }}">Ver</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($Aprobadas) > 0)
                        <div class="d-flex justify-content-end">
                            {{ $Aprobadas->appends(request()->input())->links() }}
                        </div>
                    @endif
                </div>



                {{-- Tabla para canceladas por el usuario --}}

            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" id="modalDetails" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Detalles de la solicitud</h5>
                        <div id="modalId" style="display: none;"></div>
                        <button id="closeModalDetails" type="button" class="btn-close" aria-label="Close"></button>

                    </div>
                    <div class="modal-body">
                        <form id="miFormularioAproveRh" action="authorization/by/human/resources" method="POST">
                            @csrf

                            <div class="d-flex justify-content-end">
                                <span class="mr-1">Fecha de creación: </span>
                                <span id="modalCreate"></span>
                            </div>

                            <div class="row">
                                <div class="col-3">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <img id="modalImage" alt="Imagen de perfil"
                                            style="width: 70%; height: 120px; border-radius: 100px;">
                                    </div>
                                </div>
                                <div class="col-9  mt-2">
                                    <div>
                                        <strong id="modalName"></strong>
                                    </div>

                                    <div class="mt-2">
                                        <span>Tienes
                                            <strong id="modalCurrentVacation"></strong>
                                            día(s) disponible(s) que vence(n) el
                                            <strong id="primaryPeriodo"></strong>
                                        </span>
                                    </div>


                                    <div class="mt-2" id="secondaryPeriodo">
                                        <span>Tienes
                                            <strong id="modalNextVaca"></strong>
                                            día(s) disponible(s) que vence(n) el
                                            <strong id="secondariPeriodo"></strong>
                                        </span>
                                    </div>

                                    <div class="d-flex mt-3">
                                        <div class="mr-3">
                                            <div>
                                                <span>JEFE DIRECTO</span>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <spa id="modalDirectManagerStatus" style="padding: 8px 15px"></span>

                                            </div>
                                        </div>
                                        <div>
                                            <div>
                                                <span>RECURSOS HUMANOS</span>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <span id="modalRhStatus" style="padding: 8px 15px"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3 mb-2">
                                <div class="col-3 mt-2">
                                    <strong>Tipo: </strong>
                                </div>
                                <div class="col-9  mt-2">
                                    <span id="modalRequestType"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Tipo específico: </strong>
                                </div>
                                <div class="col-9  mt-2">
                                    <span id="modalSpecificType"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Días ausente: </strong>
                                </div>

                                <div class="col-9  mt-2">
                                    <span id="modalDaysAbsent"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Forma de pago: </strong>
                                </div>

                                <div class="col-9  mt-2">
                                    <span id="modalMethodOfPayment"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Tiempo: </strong>
                                </div>

                                <div class="col-9  mt-2">
                                    <span id="timeStatus">Tiempo completo</span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Apoyo: </strong>
                                </div>

                                <div class="col-9  mt-2">
                                    <span id="modalRevealId"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Justificante: </strong>
                                </div>

                                <div class="col-9  mt-2">
                                    <div id="viewFile">
                                        {{-- <a id="file" href="" target="_blank">Ver archivo</a> --}}
                                    </div>
                                </div>
                            </div>

                            <div id="buttonModifi" class="d-none">
                                <button id="denyRequest" type="button" class="btn btn-danger mr-2">Rechazar</button>
                                <button type="button" class="btn btn-primary" id="approveButton">Aprobar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop


@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/all.min.css') }}">
    <style>
        table.dataTable td {
            padding: 15px 8px;
        }

        .fontawesome-icons .the-icon svg {
            font-size: 24px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />x1

    <style>
        .tarjetaRh1 {
            border: 0px solid rgb(243, 243, 243);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            cursor: pointer;
        }

        .tarjetaRh1:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .tarjetaRh1 strong {
            color: var(--color-target-1);
        }

        .tarjetaRh1-activa {
            background-color: var(--color-target-1);
            color: white;
        }

        .tarjetaRh1-activa strong {
            color: white;
        }

        .tarjetaRh2 {
            border: 0px solid rgb(243, 243, 243);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            cursor: pointer;
        }

        .tarjetaRh2:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .tarjetaRh2 strong {
            color: var(--color-target-3);
        }

        .tarjetaRh2-activa {
            background-color: var(--color-target-3);
            color: white;
        }

        .tarjetaRh2-activa strong {
            color: white;
        }

        .tarjetaRh3 {
            border: 0px solid rgb(243, 243, 243);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            background-color: white;
            cursor: pointer;
        }

        .tarjetaRh3:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .tarjetaRh3 strong {
            color: var(--color-target-4);
        }

        .tarjetaRh3-activa {
            background-color: var(--color-target-4);
            color: white !important;
        }

        .tarjetaRh3-activa strong {
            color: white;
        }

        .hover-tarjetaRh1:hover {
            background-color: var(--color-target-1);
            color: white;
        }

        .hover-tarjetaRh1:hover strong {
            color: white;
        }

        .hover-tarjetaRh2:hover {
            background-color: var(--color-target-3);
            /*Degradar el color*/
            color: white;
        }

        .hover-tarjetaRh2:hover strong {
            color: white;
        }

        .hover-tarjetaRh3:hover {
            background-color: var(--color-target-4);
            color: white;
        }

        .hover-tarjetaRh3:hover strong {
            color: white;
        }


        /*Color para el badge de aprobado*/
        .bg-success {
            background-color: #81C10C !important;
        }

        .bg-warning {
            background-color: #FFC107 !important;
        }

        /*Estilos de las alertas*/
        .alert {
            padding: 0.7rem !important;
        }

        .alert-success {
            color: #0f5132 !important;
            background-color: #d1e7dd !important;
            border-color: #badbcc !important;
        }

        .alert-danger {
            color: #C10C0C !important;
            background-color: #f8d7da !important;
            border-color: #f5c2c7 !important;
        }

        .alert-warning {
            color: #664d03 !important;
            background-color: #fff3cd !important;
            border-color: #ffecb5 !important;
        }


        /*Estilos de paginacion*/
        .pagination {
            display: flex;
            justify-content: end;

        }

        .page-item .page-link {
            font-size: .875rem;
            border-color: transparent;
        }

        .page-item.active .page-link {
            background-color: #435ebe;
            border-color: #435ebe;
            color: #fff;
            z-index: 3;
            border-radius: 27px;
        }

        .page-item.disabled .page-link {
            background-color: #fff;
            color: #6c757d;
            pointer-events: none;
            border-color: transparent;
        }

        /*Estilo para spin de carga*/
        #loadingSpinner {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@stop

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select2').select2();
        });
    </script>

    <script>
        function viewFileDeny(file) {
            console.log('file', file);
            const correctedFile = file.replace(/\\/g, '/');

            const baseUrl = window.location.origin;

            const fileUrl = baseUrl + '/' + correctedFile;

            window.open(fileUrl, '_blank');
        }




        //Poner por defecto la tarjeta 1 activa
        document.getElementById('tarjeta1').classList.add('tarjetaRh1-activa');


        document.getElementById('tarjeta2').addEventListener('click', function() {

            document.getElementById('loadingSpinner').style.display = 'flex';

            window.location.href = '/request/authorize-rh/pendientes';

        });

        document.getElementById('tarjeta3').addEventListener('click', function() {
            document.getElementById('loadingSpinner').style.display = 'flex';
            window.location.href = '/request/authorize-rh/rechazadas';
        });

        // Evento cuando se hace clic en el botón para abrir el modal
        document.querySelectorAll('.openModalDetails').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('modalId').textContent = id;

                const modal = new bootstrap.Modal(document.getElementById('modalDetails'));
                modal.show();
                const create = this.getAttribute('data-crete');
                const image = this.getAttribute('data-image');

                const date = new Date(create);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate = date.toLocaleDateString('es-ES', options);
                document.getElementById('modalCreate').textContent = formattedDate;
                let imagen = '';


                if (image === null || image === 'null' || image === 'undefined' || image === undefined ||
                    image === '') {
                    console.log('esta vacio');
                    /*Poner una imagen de un perfil*/
                    imagen = 'https://www.w3schools.com/howto/img_avatar.png';
                } else {
                    const baseUrl = window.location.origin;
                    /* Poner anexarle la baseURl a la imagen */
                    imagen = baseUrl + '/' + image;
                }

                document.getElementById('modalImage').src = imagen;
                const name = this.getAttribute('data-name');
                const current_vacation = this.getAttribute('data-current_vacation');
                const current_vacation_expiration = this.getAttribute('data-current_vacation_expiration');
                const next_vacation = this.getAttribute('data-next_vacation');
                const expiration_of_next_vacation = this.getAttribute('data-expiration_of_next_vacation');
                const direct_manager_status = this.getAttribute('data-direct_manager_status');
                const rh_status = this.getAttribute('data-rh_status');
                const request_type = this.getAttribute('data-request_type');
                const specific_type = this.getAttribute('data-specific_type');
                const days_absent = this.getAttribute('data-days_absent');
                const method_of_payment = (request_type === 'Vacaciones' || request_type === 'vacaciones') ?
                    'A cuenta de vacaciones' :
                    request_type === 'Ausencia' ?
                    'Descontar Tiempo/Día' :
                    (request_type === 'Paternidad' || request_type === 'Permisos especiales') ?
                    'Permiso Especial' :
                    request_type === 'Incapacidad' ?
                    'Pago del IMSS' :
                    'Sin especificar';
                const reveal_id = this.getAttribute('data-reveal_id');
                const file = this.getAttribute('data-file');

                if (file === 'No hay justificante' || file === '' || file === null || file === 'null' ||
                    file === undefined) {
                    // Crea la etiqueta <span> con el texto "No hay justificante"
                    const span = document.createElement('span');
                    span.textContent = 'No hay justificante';
                    // Agrega el span al div
                    document.getElementById('viewFile').innerHTML = '';
                    document.getElementById('viewFile').appendChild(span);
                } else {
                    const baseUrl = window.location.origin;
                    const viewFile = baseUrl + '/' + file;
                    // Crea la etiqueta <a> y establece su href dinámicamente
                    const link = document.createElement('a');
                    link.id = 'file';
                    link.href = viewFile;
                    link.target = '_blank';
                    link.textContent = 'Ver archivo';
                    // Agrega el enlace al div
                    document.getElementById('viewFile').innerHTML = '';
                    document.getElementById('viewFile').appendChild(link);
                }

                const timeText = this.getAttribute('data-timeArray');
                const dataStart = this.getAttribute('data-start');
                const dataEnd = this.getAttribute('data-end');

                /* Para Ausencia */
                const valueType = this.getAttribute('data-value-type');
                console.log('valueType', valueType);
                const tipoDeAusencia = this.getAttribute('data-tipo-de-ausencia');

                /* Para permisos especiales */
                const typePermisoEspecial = this.getAttribute('data-tipo-permiso-especial');

                var timeStatusDiv = document.getElementById('timeStatus');
                if (timeText === 'true') {
                    var startValue = dataStart;
                    var endValue = dataEnd;

                    if (request_type === 'Ausencia' && valueType === 'salida_durante') {
                        timeStatusDiv.innerHTML =
                            '<span>Hora de salida: <strong>' + startValue + '</strong></span> ' +
                            '- Hora de regreso: <strong>' + endValue + '</strong></span>';
                    } else if (request_type === 'Ausencia' && valueType === 'salida_antes') {
                        timeStatusDiv.innerHTML = '<span>Hora de salida: <strong>' + startValue +
                            '</strong></span> ';
                    } else if (request_type === 'Ausencia' && valueType === 'retardo') {
                        timeStatusDiv.innerHTML = '<span>Hora de entrada: <strong>' + endValue +
                            '</strong></span> ';
                    } else {
                        timeStatusDiv.innerHTML = '<span>Tiempo Completo</span>';
                    }
                } else {
                    timeStatusDiv.innerHTML = '<span>Tiempo Completo</span>';
                }

                const date2 = new Date(current_vacation_expiration);
                date2.setDate(date2.getDate() + 1);
                const options2 = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate2 = date2.toLocaleDateString('es-ES', options2);

                const date3 = new Date(expiration_of_next_vacation);
                date3.setDate(date3.getDate() + 1);
                const options3 = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate3 = date3.toLocaleDateString('es-ES', options3);


                document.getElementById('modalName').textContent = name;
                document.getElementById('modalCurrentVacation').textContent = current_vacation;
                document.getElementById('primaryPeriodo').textContent = formattedDate2;
                document.getElementById('modalNextVaca').textContent = next_vacation || 'No hay vacaciones';

                const secondaryPeriodo = document.getElementById('secondaryPeriodo');
                if (modalNextVaca.textContent === 'No hay vacaciones') {
                    secondaryPeriodo.className = 'd-none';
                } else {
                    secondaryPeriodo.className = 'd-flex';
                }

                document.getElementById('secondariPeriodo').textContent = formattedDate3;

                document.getElementById('modalDirectManagerStatus').textContent = direct_manager_status;
                const modalDirectManagerStatus = document.getElementById('modalDirectManagerStatus');
                // Verificar el valor y asignar el contenido y las clases
                if (direct_manager_status === 'Pendiente') {
                    modalDirectManagerStatus.textContent = 'Pendiente';
                    modalDirectManagerStatus.className = 'badge bg-warning text-dark';
                } else if (direct_manager_status === 'Aprobada') {
                    modalDirectManagerStatus.textContent = 'Aprobada';
                    modalDirectManagerStatus.className = 'badge bg-success';
                } else if (direct_manager_status === 'Rechazada') {
                    modalDirectManagerStatus.textContent = 'Rechazada';
                    modalDirectManagerStatus.className = 'badge bg-danger';
                } else {
                    modalDirectManagerStatus.textContent = 'Desconocido';
                    modalDirectManagerStatus.className = 'badge bg-secondary';
                }


                document.getElementById('modalRhStatus').textContent = rh_status;
                const modalRhStatus = document.getElementById('modalRhStatus');
                // Verificar el valor y asignar el contenido y las clases
                if (rh_status === 'Pendiente') {
                    modalRhStatus.textContent = 'Pendiente';
                    modalRhStatus.className = 'badge bg-warning text-dark';
                } else if (rh_status === 'Aprobada') {
                    modalRhStatus.textContent = 'Aprobada';
                    modalRhStatus.className = 'badge bg-success';
                } else if (rh_status === 'Rechazada') {
                    modalRhStatus.textContent = 'Rechazada';
                    modalRhStatus.className = 'badge bg-danger';
                } else {
                    modalRhStatus.textContent = 'Desconocido';
                    modalRhStatus.className = 'badge bg-secondary';
                }

                if (request_type === 'Ausencia') {
                    document.getElementById('modalSpecificType').textContent = tipoDeAusencia;
                } else if (request_type === 'Permisos especiales') {
                    document.getElementById('modalSpecificType').textContent =
                        typePermisoEspecial;
                } else {
                    document.getElementById('modalSpecificType').textContent = 'Sin especificar';
                }

                document.getElementById('modalRequestType').textContent = request_type;
                document.getElementById('modalDaysAbsent').textContent = days_absent;
                document.getElementById('modalMethodOfPayment').textContent = method_of_payment;
                document.getElementById('modalRevealId').textContent = reveal_id;
            });
        });

        /* Cerrar el modal de detalles */
        document.getElementById('closeModalDetails').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            document.getElementById('buttonModifi').style.display = 'none';

        });

        let tiempoEspera;
        //Filtrado de solicitudes
        document.getElementById('searchName').addEventListener('input', function() {
            clearTimeout(tiempoEspera);
            tiempoEspera = setTimeout(function() {
                console.log('Buscando...');
                applyFilters();
            }, 750);
        });


        document.getElementById('tipoSelect').addEventListener('change', function() {
            applyFilters();
        });

        document.getElementById('fechaInput').addEventListener('change', function() {
            applyFilters();
        });

        document.getElementById('clearFilter').addEventListener('click', function() {
            document.getElementById('fechaInput').value = '';
            document.getElementById('tipoSelect').value = '';
            document.getElementById('searchName').value = '';
            applyFilters();
        });

        /*Funcion para aplicar los filtros*/
        function applyFilters() {
            document.getElementById('loadingSpinner').style.display = 'flex';

            const tipo = document.getElementById('tipoSelect').value;
            const fecha = document.getElementById('fechaInput').value;
            const search = document.getElementById('searchName').value;

            let url = '?tipo=' + tipo + '&fecha=' + fecha + '&search=' + encodeURIComponent(search);
            window.location.href = url;
        }

        // Mostrar el spinner de carga al cambiar de página
        document.querySelectorAll('.pagination a').forEach(function(link) {
            link.addEventListener('click', function(event) {
                document.getElementById('loadingSpinner').style.display = 'flex';
            });
        });



        //Si en la url esta el parametro de search pasarlo al input de busqueda searchName
        const urlParams = new URLSearchParams(window.location.search);
        const search = urlParams.get('search');
        if (search) {
            document.getElementById('searchName').value = search;
        }


        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('loadingSpinner').style.display = 'flex';

            axios({
                method: 'post',
                url: this.action,
                data: new FormData(this),
                responseType: 'blob'
            }).then(function(response) {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;

                link.setAttribute('download',
                    'Solicitudes aprobadas.xlsx');
                document.body.appendChild(link);
                link.click();

                window.URL.revokeObjectURL(url);

                document.getElementById('loadingSpinner').style.display = 'none';
            }).catch(function(error) {
                console.error('Error al exportar:', error);

                document.getElementById('loadingSpinner').style.display = 'none';
            });
        });
    </script>
@stop
