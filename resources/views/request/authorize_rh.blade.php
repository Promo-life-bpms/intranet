@extends('layouts.app')

@section('content')

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

            <div id="buttonUpdateDays" style="display: none">
                <button class="btn" style="background-color: var(--color-target-1); color: white; ">Actualizar</button>
            </div>


            <div class="mt-3" style="min-width: 100% !important;">

                {{-- Tabla para autorizadas --}}
                <div id="tableAutorizadas">
                    <table class="table" style="min-width: 100% !important;">
                        <thead style="background-color: #072A3B; color: white;">
                            <tr>
                                <th scope="col" style="text-align: center;">#</th>
                                <th scope="col" style="text-align: center;">Solicitante</th>
                                <th scope="col" style="text-align: center;">Tipo de Solicitud</th>
                                <th scope="col" style="text-align: center;">Dias ausente</th>
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
                                            data-crete="{{ $aprovada['created_at'] }}" data-name="{{ $aprovada['name'] }}"
                                            data-image="{{ $aprovada['image'] }}"
                                            data-current_vacation="{{ $aprovada['current_vacation'] }}"
                                            data-current_vacation_expiration="{{ $aprovada['current_vacation_expiration'] }}"
                                            data-next_vacation="{{ $aprovada['next_vacation'] }}"
                                            data-expiration_of_next_vacation="{{ $aprovada['expiration_of_next_vacation'] }}"
                                            data-direct_manager_status="{{ $aprovada['direct_manager_status'] }}"
                                            data-rh_status="{{ $aprovada['rh_status'] }}"
                                            data-request_type="{{ $aprovada['request_type'] }}"
                                            data-specific_type="{{ $aprovada['specific_type'] }}"
                                            data-days_absent="{{ implode(',', $aprovada['days_absent']) }}"
                                            data-method_of_payment="{{ $aprovada['method_of_payment'] }}"
                                            data-time="{{ $aprovada['time'] }}"
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
                                <th scope="col" style="text-align: center;">Tipo de Solicitud</th>
                                <th scope="col" style="text-align: center;">Dias ausente</th>
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
                                            data-method_of_payment="{{ $pendiente['method_of_payment'] }}"
                                            data-time="{{ $pendiente['time'] }}"
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
                                <th scope="col" style="text-align: center;">Tipo de Solicitud</th>
                                <th scope="col" style="text-align: center;">Dias ausente</th>
                                <th scope="col" style="text-align: center;">Justificante</th>
                                <th scope="col" style="text-align: center;">Motivo</th>
                                <th scope="col" style="text-align: center;">Reposicion</th>
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

                                        <span class="text-align: center;">
                                            {{ $rechazada['file'] }}
                                        </span>

                                    </td>
                                    <td style="text-align: center;">
                                        {{ $rechazada['commentary'] }}
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="#" class="btn btn-link">
                                            <input type="number" style="max-width: 30%; text-align: center;">
                                        </a>
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
                        <h5 class="modal-title" id="modalTitle">Detalles de la Solicitud</h5>
                        <div id="modalId" style="display: none;"></div>
                        <button id="closemodalDetails" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
                                        <span>Te queda
                                            <strong id="modalCurrentVacation"></strong>
                                            días disponible que vence el
                                            <strong id="modalCurrentVacationExpiration"></strong>
                                        </span>
                                    </div>


                                    <div class="mt-2" id="secondaryPeriodo">
                                        <span>Te queda
                                            <strong id="modalNextVaca"></strong>
                                            días disponible que vence el
                                            <strong id="modalExpireNextVaca"></strong>
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
                                        <strong>Tipo especifico: </strong>
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
                                        <strong>Justificantes: </strong>
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

                                    <div class="mt-2">
                                        <span id="modalTime"></span>
                                    </div>

                                    <div class="mt-2">
                                        <span id="modalRevealId"></span>
                                    </div>

                                    <div class="mt-2">
                                        <span id="modalFile"></span>
                                    </div>
                                </div>
                            </div>

                            <div id="buttonModifi" style="display: none">
                                <button id="denyRequest" type="button" class="btn btn-danger mr-2">Rechazar</button>
                                <button type="button" class="btn btn-primary" id="approveButton">Aprobada</button>
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
                        <button id="closeModalDeny" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="denyFormRequest" action="reject/leave/by/human/resources" method="POST">
                            @csrf
                            <textarea style="min-width: 100%" class="form-control" id="commentary" name="commentary" required></textarea>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="button" class="btn btn-primary" id="denyButtonForm">Aprobada</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('styles')
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
    </style>

@stop

@section('scripts')
    <script>
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
            //Desabilitar boton de actualizar dias
            document.getElementById('buttonUpdateDays').style.display = 'none';
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
            //Desabilitar boton de actualizar dias
            document.getElementById('buttonUpdateDays').style.display = 'none';
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
            //Habilitar boton de actualizar dias
            document.getElementById('buttonUpdateDays').style.display = 'flex';
            document.getElementById('buttonUpdateDays').style.justifyContent = 'flex-end';
            document.getElementById('buttonUpdateDays').style.marginTop = '13px';
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
                const method_of_payment = this.getAttribute('data-method_of_payment');
                const time = this.getAttribute('data-time');
                const reveal_id = this.getAttribute('data-reveal_id');
                const file = this.getAttribute('data-file');

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
                document.getElementById('modalCurrentVacationExpiration').textContent = formattedDate2;
                document.getElementById('modalNextVaca').textContent = next_vacation || 'No hay vacaciones';

                const modalNextVacationExpiration = document.getElementById('modalNextVacation');
                const secondaryPeriodo = document.getElementById('secondaryPeriodo');

                if (modalNextVaca.textContent === 'No hay vacaciones') {
                    secondaryPeriodo.className = 'd-none';
                } else {
                    secondaryPeriodo.className = 'd-flex';
                }

                document.getElementById('modalExpireNextVaca').textContent = formattedDate3;

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
                document.getElementById('modalRequestType').textContent = request_type;
                document.getElementById('modalSpecificType').textContent = specific_type;
                document.getElementById('modalDaysAbsent').textContent = days_absent;
                document.getElementById('modalMethodOfPayment').textContent = method_of_payment;
                document.getElementById('modalTime').textContent = time;
                document.getElementById('modalRevealId').textContent = reveal_id;
                document.getElementById('modalFile').textContent = file ? file : 'Sin justificante';
            });
        });

        document.querySelectorAll('.updateDetails').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('modalId').textContent = id;

                const modal = new bootstrap.Modal(document.getElementById('modalDetails'));
                modal.show();

                document.getElementById('buttonModifi').style.display = 'flex';
                document.getElementById('buttonModifi').style.justifyContent = 'flex-end';


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
                const method_of_payment = this.getAttribute('data-method_of_payment');
                const time = this.getAttribute('data-time');
                const reveal_id = this.getAttribute('data-reveal_id');
                const file = this.getAttribute('data-file');

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
                document.getElementById('modalCurrentVacationExpiration').textContent = formattedDate2;
                document.getElementById('modalNextVaca').textContent = next_vacation || 'No hay vacaciones';

                const modalNextVacationExpiration = document.getElementById('modalNextVacation');
                const secondaryPeriodo = document.getElementById('secondaryPeriodo');

                if (modalNextVaca.textContent === 'No hay vacaciones') {
                    secondaryPeriodo.className = 'd-none';
                } else {
                    secondaryPeriodo.className = 'd-flex';
                }

                document.getElementById('modalExpireNextVaca').textContent = formattedDate3;

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
                document.getElementById('modalRequestType').textContent = request_type;
                document.getElementById('modalSpecificType').textContent = specific_type;
                document.getElementById('modalDaysAbsent').textContent = days_absent;
                document.getElementById('modalMethodOfPayment').textContent = method_of_payment;
                document.getElementById('modalTime').textContent = time;
                document.getElementById('modalRevealId').textContent = reveal_id;
                document.getElementById('modalFile').textContent = file ? file : 'Sin justificante';
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

        document.getElementById('denyButtonForm').addEventListener('click', function() {
            const form = document.getElementById('denyFormRequest');
            /*Mandar el ID de la solicitus que se va a aprobar del que viene en el modal*/
            const id = document.getElementById('modalId').textContent;
            const inputId = document.createElement('input');
            inputId.type = 'hidden';
            inputId.name = 'id';
            inputId.value = id;
            form.appendChild(inputId);

            // Imprimir todos los datos que se enviarán por consola
            form.submit();
        });

        document.getElementById('denyRequest').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            $('#modalDeny').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
        });


        // Evento cuando se hace clic en el botón para cerrar el modal
        document.getElementById('closemodalDetails').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            document.getElementById('buttonModifi').style.display = 'none';

        });
    </script>
@stop
