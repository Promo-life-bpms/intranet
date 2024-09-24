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

    <div>
        <h3 id="titlePage">Solicitudes autorizadas</h3>
        <div class="row">
            <div class="col-5 align-content-end">
                <div class="d-flex justify-between">
                    <input id="searchName" type="search" class="form-control mr-2" placeholder="Buscar por nombre">
                    <select id="tipoSelect" style="width: max-content" class="form-select mr-1"
                        aria-label="Default select example">
                        <option value="">Todos</option>
                        <option value="Vacaciones">Vacaciones</option>
                        <option value="ausencia">Ausencia</option>
                        <option value="permiso_especial">Permisos especiales</option>
                        <option value="incapacidad">Incapacidad</option>
                        <option value="paternidad">Paternidad</option>
                    </select>

                    <input id="fechaInput" placeholder="Fecha" type="date" class="form-control" style="width: 2.8rem" />
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
                            style="border-bottom: 10px solid var(--color-target-4); min-height: 100%; padding: 10px 20px !important;">
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

            <div id="buttonUpdateDays" class="d-flex justify-content-end mt-3">
                <button id="buttonReposicion" class="btn"
                    style="background-color: var(--color-target-1); color: white; ">Reposición</button>
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
                            @foreach ($Aprobadas as $aprovada)
                                <tr class="solicitud-row1" data-days1="{{ implode(',', $aprovada['days_absent']) }}">
                                    <th style="text-align: center; align-content: center;" scope="row">
                                        {{ $aprovada['id'] }}</th>
                                    <td style="text-align: center;">{{ $aprovada['name'] }}</td>
                                    <td style="text-align: center;">{{ $aprovada['request_type'] }}</td>
                                    <td style="text-align: center;">
                                        @foreach ($aprovada['days_absent'] as $day)
                                            <div>
                                                {{ $day }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($aprovada['direct_manager_status'] == 'Pendiente')
                                            <span class="badge bg-warning text-dark">
                                                {{ $aprovada['direct_manager_status'] }}
                                            </span>
                                        @elseif ($aprovada['direct_manager_status'] == 'Aprobada')
                                            <span class="badge bg-success text-white">
                                                {{ $aprovada['direct_manager_status'] }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ $aprovada['direct_manager_status'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($aprovada['rh_status'] == 'Pendiente')
                                            <span class="badge bg-warning text-dark">
                                                {{ $aprovada['rh_status'] }}
                                            </span>
                                        @elseif ($aprovada['rh_status'] == 'Aprobada')
                                            <span class="badge bg-success text-white">
                                                {{ $aprovada['rh_status'] }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ $aprovada['rh_status'] }}
                                            </span>
                                        @endif
                                    </td>

                                    <td style="text-align: center; cursor: pointer;">
                                        <button class="btn btn-link openModalDetails" data-id="{{ $aprovada['id'] }}"
                                            data-crete="{{ $aprovada['created_at'] }}"
                                            data-name="{{ $aprovada['name'] }}" data-image="{{ $aprovada['image'] }}"
                                            data-current_vacation="{{ $aprovada['current_vacation'] }}"
                                            data-current_vacation_expiration="{{ $aprovada['current_vacation_expiration'] }}"
                                            data-next_vacation="{{ $aprovada['next_vacation'] }}"
                                            data-expiration_of_next_vacation="{{ $aprovada['expiration_of_next_vacation'] }}"
                                            data-direct_manager_status="{{ $aprovada['direct_manager_status'] }}"
                                            data-rh_status="{{ $aprovada['rh_status'] }}"
                                            data-request_type="{{ $aprovada['request_type'] }}"
                                            data-specific_type="{{ $aprovada['specific_type'] }}"
                                            data-days_absent="{{ implode(',', $aprovada['days_absent']) }}"
                                            data-timeArray="{{ is_Array($aprovada['time']) ? 'true' : 'false' }}"
                                            data-start="{{ $aprovada['time'] ? $aprovada['time'][0]['start'] : '12:00' }}"
                                            data-end="{{ $aprovada['time'] ? $aprovada['time'][0]['end'] : '12:00' }}"
                                            {{-- Para Ausencia --}}
                                            data-value-type="{{ is_array($aprovada['more_information']) && count($aprovada['more_information']) > 0 && isset($aprovada['more_information'][0]['value_type']) ? $aprovada['more_information'][0]['value_type'] : '0' }}"
                                            data-tipo-de-ausencia="{{ is_array($aprovada['more_information']) && isset($aprovada['more_information'][0]['Tipo_de_ausencia']) ? $aprovada['more_information'][0]['Tipo_de_ausencia'] : '-' }}"
                                            {{-- Para permisos especiales --}}
                                            data-tipo-permiso-especial="{{ is_array($aprovada['more_information']) && count($aprovada['more_information']) > 0 && isset($aprovada['more_information'][0]['Tipo_de_permiso_especial']) ? $aprovada['more_information'][0]['Tipo_de_permiso_especial'] : '-' }}"
                                            data-reveal_id="{{ $aprovada['reveal_id'] }}"
                                            data-file="{{ $aprovada['file'] }}">Ver</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                {{-- Tabla para pendientes --}}
                <div id="tablePendientes" style="display: none">
                    <table class="table" style="min-width: 100% !important;">
                        <thead style="background-color: #072A3B; color: white;">
                            <tr>
                                <th scope="col" style="text-align: center; align-content: center;">#</th>
                                <th scope="col" style="text-align: center;">Solicitante</th>
                                <th scope="col" style="text-align: center;">Tipo de solicitud</th>
                                <th scope="col" style="text-align: center;">Días ausente</th>
                                <th scope="col" style="text-align: center;">Aprobado por (Jefe)</th>
                                <th scope="col" style="text-align: center;">Autorizar solicitud</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($SolicitudesPendientes as $pendiente)
                                <tr class="solicitud-row2" data-days2="{{ implode(',', $pendiente['days_absent']) }}">
                                    <th style="text-align: center; align-content: center;" scope="row">
                                        {{ $pendiente['id'] }}</th>
                                    <td style="text-align: center;">{{ $pendiente['name'] }}</td>
                                    <td style="text-align: center;">{{ $pendiente['request_type'] }}</td>
                                    <td style="text-align: center;">
                                        @foreach ($pendiente['days_absent'] as $day)
                                            <div>
                                                {{ $day }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($pendiente['direct_manager_status'] == 'Pendiente')
                                            <span class="badge bg-warning text-dark">
                                                {{ $pendiente['direct_manager_status'] }}
                                            </span>
                                        @elseif ($pendiente['direct_manager_status'] == 'Aprobada')
                                            <span class="badge bg-success text-white">
                                                {{ $pendiente['direct_manager_status'] }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white">
                                                {{ $pendiente['direct_manager_status'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center; cursor: pointer;">
                                        <button class="btn btn-link updateDetails" data-id="{{ $pendiente['id'] }}"
                                            data-crete="{{ $pendiente['created_at'] }}"
                                            data-name="{{ $pendiente['name'] }}" data-image="{{ $pendiente['image'] }}"
                                            data-current_vacation="{{ $pendiente['current_vacation'] }}"
                                            data-current_vacation_expiration="{{ $pendiente['current_vacation_expiration'] }}"
                                            data-next_vacation="{{ $pendiente['next_vacation'] }}"
                                            data-expiration_of_next_vacation="{{ $pendiente['expiration_of_next_vacation'] }}"
                                            data-direct_manager_status="{{ $pendiente['direct_manager_status'] }}"
                                            data-rh_status="{{ $pendiente['rh_status'] }}"
                                            data-request_type="{{ $pendiente['request_type'] }}"
                                            data-specific_type="{{ $pendiente['specific_type'] }}"
                                            data-days_absent="{{ implode(',', $pendiente['days_absent']) }}"
                                            data-timeArray="{{ is_Array($pendiente['time']) ? 'true' : 'false' }}"
                                            data-start="{{ $pendiente['time'] ? $pendiente['time'][0]['start'] : '12:00' }}"
                                            data-end="{{ $pendiente['time'] ? $pendiente['time'][0]['end'] : '12:00' }}"
                                            {{-- Para Ausencia --}}
                                            data-value-type="{{ is_array($pendiente['more_information']) && count($pendiente['more_information']) > 0 && isset($pendiente['more_information'][0]['value_type']) ? $pendiente['more_information'][0]['value_type'] : '0' }}"
                                            data-tipo-de-ausencia="{{ is_array($pendiente['more_information']) && isset($pendiente['more_information'][0]['Tipo_de_ausencia']) ? $pendiente['more_information'][0]['Tipo_de_ausencia'] : '-' }}"
                                            {{-- Para permisos especiales --}}
                                            data-tipo-permiso-especial="{{ is_array($pendiente['more_information']) && count($pendiente['more_information']) > 0 && isset($pendiente['more_information'][0]['Tipo_de_permiso_especial']) ? $pendiente['more_information'][0]['Tipo_de_permiso_especial'] : '-' }}"
                                            data-reveal_id="{{ $pendiente['reveal_id'] }}"
                                            data-file="{{ $pendiente['file'] }}">Ver y Autorizar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Tabla para canceladas por el usuario --}}
                <div id="tableCanceladas" style="display: none">
                    <table class="table" id="tableCanceladas" style="min-width: 100% !important;">
                        <thead style="background-color: #072A3B; color: white;">
                            <tr>
                                <th scope="col" style="text-align: center;">#</th>
                                <th scope="col" style="text-align: center;">Solicitante</th>
                                <th scope="col" style="text-align: center;">Tipo de solicitud</th>
                                <th scope="col" style="text-align: center;">Días ausente</th>
                                <th scope="col" style="text-align: center;">Justificante</th>
                                <th scope="col" style="text-align: center;">Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rechazadas as $rechazada)
                                <tr class="solicitud-row3" data-days3="{{ implode(',', $rechazada['days_absent']) }}">
                                    <th style="text-align: center; align-content: center;" scope="row">
                                        {{ $rechazada['id'] }}</th>
                                    <td style="text-align: center;">{{ $rechazada['name'] }}</td>
                                    <td style="text-align: center;">{{ $rechazada['request_type'] }}</td>
                                    <td style="text-align: center;">
                                        @foreach ($rechazada['days_absent'] as $day)
                                            <div>
                                                {{ $day }}
                                            </div>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" id="file-link-{{ $rechazada['file'] }}"
                                            class="btn btn-link"
                                            onclick="viewFileDeny({{ json_encode($rechazada['file']) }})">Ver
                                            archivo</button>
                                    </td>
                                    <td style="text-align: center;">
                                        {{ $rechazada['commentary'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                                <div class="col-9">
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
                                <div class="col-3">
                                    <div class="mt-2">
                                        <strong>Tipo: </strong>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Tipo específico: </strong>
                                    </div>
                                    <div class="mt-2">
                                        <strong>Días ausente: </strong>
                                    </div>

                                    <div class="mt-2">
                                        <strong>Forma de pago: </strong>
                                    </div>

                                    <div class="mt-2">
                                        <strong>Tiempo: </strong>
                                    </div>

                                    <div class="mt-2">
                                        <strong>Apoyo: </strong>
                                    </div>

                                    <div class="mt-2">
                                        <strong>Justificante: </strong>
                                    </div>
                                </div>

                                <div class="col-9">
                                    <div class="mt-2">
                                        <span id="modalRequestType"></span>
                                    </div>
                                    <div class="mt-2">
                                        <span id="modalSpecificType"></span>
                                    </div>
                                    <div class="mt-2">
                                        <span id="modalDaysAbsent"></span>
                                    </div>

                                    <div class="mt-2">
                                        <span id="modalMethodOfPayment"></span>
                                    </div>

                                    <div class="mt-2" id="timeStatus">
                                        <span>Tiempo completo</span>
                                    </div>

                                    <div class="mt-2">
                                        <span id="modalRevealId"></span>
                                    </div>

                                    <div class="mt-2" id="viewFile">
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

        <div class="modal fade bd-example-modal-lg" id="modalDeny" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Rechazar solicitud</h5>
                        <div id="modalId" style="display: none;"></div>
                        <button id='closeModalDeny' type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="denyFormRequest" action="reject/leave/by/human/resources" method="POST">
                            @csrf
                            <textarea style="min-width: 100%" class="form-control" id="commentary" name="commentary" placeholder="Motivo"
                                required></textarea>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-primary" id="denyButtonForm">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para reposición -->
        <div class="modal fade bd-example-modal-lg" id="modalReposicion" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Aumento/Descuento de días</h5>
                        <button id="ButtonCloseReposicion" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="make/up/vacations" method="POST" id="formReposicion">
                            @csrf
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <span>Selecciona usuarios:</span>
                                    <select name="team[]" class="form-select" multiple
                                        data-placeholder="Selecciona Usuarios" id="select2" data-coreui-search="true"
                                        style="width: 100%">
                                        @foreach ($IdandNameUser as $user)
                                            <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <span>Días: </span>
                                    <input name='days' id="days" type="number" min="0"
                                        class="form-control" placeholder="Días">
                                </div>

                                <div class="col-4">
                                    <div class="mr-4 mb-3">
                                        <span>Selecciona el periodo: </span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="Periodo"
                                                id="primer_periodo" value="primer_periodo">
                                            <label class="form-check-label" for="primer_periodo">
                                                Primero
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="Periodo"
                                                id="segundo_periodo" value="segundo_periodo">
                                            <label class="form-check-label" for="segundo_periodo">
                                                Segundo
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="mr-4 mb-3">
                                        <span>Opción: </span>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="Opcion"
                                                id="aumentar_dias" value="aumentar_dias">
                                            <label class="form-check-label" for="aumentar_dias">
                                                Aumentar
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="Opcion"
                                                id="descontar_dias" value="descontar_dias">
                                            <label class="form-check-label" for="descontar_dias">
                                                Descontar
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <textarea style="min-width: 100%" class="form-control mt-2" id="commentary" name="commentary" placeholder="Motivo"
                                        required></textarea>
                                </div>

                                <div class="d-flex justify-content-end mt-2">
                                    <button id="acepForm" class="btn btn-primary" type="submit">Aceptar</button>
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
            color: #842029 !important;
            background-color: #f8d7da !important;
            border-color: #f5c2c7 !important;
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
            const correctedFile = file.replace(/\\/g, '/');

            const baseUrl = window.location.origin;

            const fileUrl = baseUrl + '/' + correctedFile;

            window.open(fileUrl, '_blank');
        }


        /*Rechazar solicitud*/
        document.getElementById('denyButtonForm').addEventListener('click', function() {
            const form = document.getElementById('denyFormRequest');
            /*Mandar el ID de la solicitus que se va a aprobar del que viene en el modal*/
            const id = document.getElementById('modalId').textContent;
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            form.appendChild(inputId);

            form.submit();
        });

        /*Limpiar el formulario de rechazo*/
        document.getElementById('closeModalDeny').addEventListener('click', function() {
            document.getElementById('commentary').value = '';
        });

        /*Limpiar ek formulario de agregar o quitar dias*/
        document.getElementById('ButtonCloseReposicion').addEventListener('click', function() {
            const form = document.getElementById('formReposicion');
            form.reset();
            // Limpiar el select múltiple de Select2
            $('#select2').val(null).trigger('change'); // Con jQuery y Select2
        });


        //Poner por defecto la tarjeta 1 activa
        document.getElementById('tarjeta1').classList.add('tarjetaRh1-activa');

        document.getElementById('tarjeta1').addEventListener('click', function() {
            this.classList.toggle('tarjetaRh1-activa');
            ///Y removemos la clase activa de las otras tarjetas
            document.getElementById('tarjeta2').classList.remove('tarjetaRh2-activa');
            document.getElementById('tarjeta3').classList.remove('tarjetaRh3-activa');
            ///Mostrar tabla de autorizadas
            document.getElementById('tableAutorizadas').style.display = 'block';
            document.getElementById('tablePendientes').style.display = 'none';
            document.getElementById('tableCanceladas').style.display = 'none';


            //Cambiar titulo de la pagina
            document.getElementById('titlePage').innerHTML = 'Solicitudes autorizadas';
        });


        document.getElementById('tarjeta2').addEventListener('click', function() {
            this.classList.toggle('tarjetaRh2-activa');
            ///Y removemos la clase activa de las otras tarjetas
            document.getElementById('tarjeta1').classList.remove('tarjetaRh1-activa');
            document.getElementById('tarjeta3').classList.remove('tarjetaRh3-activa');
            ///Mostrar tabla de pendientes
            document.getElementById('tableAutorizadas').style.display = 'none';
            document.getElementById('tablePendientes').style.display = 'block';
            document.getElementById('tableCanceladas').style.display = 'none';


            //Cambiar titulo de la pagina
            document.getElementById('titlePage').innerHTML = 'Solicitudes pendientes';
        });

        document.getElementById('tarjeta3').addEventListener('click', function() {
            this.classList.toggle('tarjetaRh3-activa');
            ///Y removemos la clase activa de las otras tarjetas
            document.getElementById('tarjeta1').classList.remove('tarjetaRh1-activa');
            document.getElementById('tarjeta2').classList.remove('tarjetaRh2-activa');
            ///Mostrar tabla de canceladas
            document.getElementById('tableAutorizadas').style.display = 'none';
            document.getElementById('tablePendientes').style.display = 'none';
            document.getElementById('tableCanceladas').style.display = 'block';

            //Cambiar titulo de la pagina
            document.getElementById('titlePage').innerHTML = 'Solicitudes canceladas por el usuario';
        });


        /* Filtro para buscar por nombre */
        document.getElementById('searchName').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            console.log('searchValue', searchValue);
            const rows = document.querySelectorAll('#tableAutorizadas tbody tr');

            rows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const rows2 = document.querySelectorAll('#tablePendientes tbody tr');
            rows2.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const rows3 = document.querySelectorAll('#tableCanceladas tbody tr');
            rows3.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        /* Filtro para buscar por tipo de solicitud */
        document.getElementById('tipoSelect').addEventListener('change', function() {
            const searchValue = this.value.toLowerCase();
            console.log('searchValue', searchValue);
            const rows = document.querySelectorAll('#tableAutorizadas tbody tr');

            rows.forEach(row => {
                const type = row.children[2].textContent.toLowerCase();
                if (type.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const rows2 = document.querySelectorAll('#tablePendientes tbody tr');
            rows2.forEach(row => {
                const type = row.children[2].textContent.toLowerCase();
                if (type.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const rows3 = document.querySelectorAll('#tableCanceladas tbody tr');
            rows3.forEach(row => {
                const type = row.children[2].textContent.toLowerCase();
                if (type.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        /* Filtro para buscar por fecha */
        document.getElementById('fechaInput').addEventListener('input', function() {
            const searchValue = this.value;
            console.log('searchValue', searchValue);

            const rows = document.querySelectorAll('.solicitud-row1');
            rows.forEach(function(row) {
                var date = row.getAttribute('data-days1').split(',');
                if (date.includes(searchValue) || searchValue === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            const rows2 = document.querySelectorAll('.solicitud-row2');
            rows2.forEach(function(row) {
                var date = row.getAttribute('data-days2').split(',');
                if (date.includes(searchValue) || searchValue === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });


            const rows3 = document.querySelectorAll('.solicitud-row3');
            rows3.forEach(function(row) {
                var date = row.getAttribute('data-days3').split(',');
                if (date.includes(searchValue) || searchValue === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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
                const tipoDeAusencia = this.getAttribute('data-tipo-de-ausencia');

                /* Para permisos especiales */
                const typePermisoEspecial = this.getAttribute('data-tipo-permiso-especial');

                var timeStatusDiv = document.getElementById('timeStatus');
                if (timeText === 'true') {
                    var startValue = dataStar;
                    var endValue = dataEnd;

                    if (request_type === 'Ausencia' && valueType === 'salida_durante') {
                        timeStatusDiv.innerHTML =
                            '<span>Hora de salida: <strong>' + startValue + '</strong></span> ' +
                            'Hora de regreso: <strong>' + endValue + '</strong></span>';
                    } else if (request_type === 'Ausencia' && valueType === 'salida_antes') {
                        timeStatusDiv.innerHTML = '<span>Hora de salida: <strong>' + startValue +
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

        document.querySelectorAll('.updateDetails').forEach(button => {
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
                    const span = document.createElement('span');
                    span.textContent = 'No hay justificante';
                    document.getElementById('viewFile').innerHTML = '';
                    document.getElementById('viewFile').appendChild(span);
                } else {
                    const baseUrl = window.location.origin;
                    const viewFile = baseUrl + '/' + file;
                    const link = document.createElement('a');
                    link.id = 'file';
                    link.href = viewFile;
                    link.target = '_blank';
                    link.textContent = 'Ver archivo';
                    document.getElementById('viewFile').innerHTML = '';
                    document.getElementById('viewFile').appendChild(link);
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

                const timeText = this.getAttribute('data-timeArray');
                const dataStar = this.getAttribute('data-start');
                const dataEnd = this.getAttribute('data-end');

                /* Para Ausencia */
                const valueType = this.getAttribute('data-value-type');
                const tipoDeAusencia = this.getAttribute('data-tipo-de-ausencia');

                const typePermisoEspecial = this.getAttribute('data-tipo-permiso-especial');

                var timeStatusDiv = document.getElementById('timeStatus');
                if (timeText === 'true') {
                    var startValue = dataStar;
                    var endValue = dataEnd;

                    if (request_type === 'Ausencia' && valueType === 'salida_durante') {
                        timeStatusDiv.innerHTML =
                            '<span>Hora de salida: <strong>' + startValue + '</strong></span> ' +
                            'Hora de regreso: <strong>' + endValue + '</strong></span>';
                    } else if (request_type === 'Ausencia' && valueType === 'salida_antes') {
                        timeStatusDiv.innerHTML = '<span>Hora de salida: <strong>' + startValue +
                            '</strong></span> ';
                    } else if (request_type === 'Ausencia' && valueType === 'retardo') {
                        timeStatusDiv.innerHTML = '<span>Hora de llegada: <strong>' + startValue +
                            '</strong></span> ';
                    } else {
                        timeStatusDiv.innerHTML = '<span>Tiempo Completo</span>';
                    }
                } else {
                    timeStatusDiv.innerHTML = '<span>Tiempo Completo</span>';
                }


                /*Habilitar el boton de rechazar */
                document.getElementById('buttonModifi').classList.remove('d-none');
                document.getElementById('buttonModifi').style.display = 'flex';
                document.getElementById('buttonModifi').style.justifyContent = 'flex-end';


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

                /*Tipo Especifico*/
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


        document.getElementById('approveButton').addEventListener('click', function() {
            const form = document.getElementById('miFormularioAproveRh');
            /*Mandar el ID de la solicitus que se va a aprobar del que viene en el modal*/
            const id = document.getElementById('modalId').textContent;
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            form.appendChild(inputId);
            form.submit();
        });


        document.getElementById('denyRequest').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            $('#modalDeny').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
        });

        // Abrir modal reposicion
        document.getElementById('buttonReposicion').addEventListener('click', function() {
            $('#modalReposicion').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
        });
    </script>
@stop
