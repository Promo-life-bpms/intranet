@extends('layouts.app')

@section('content')

    <div>
        <div class="row">
            <div class="col-4">
                <h3>Solicitudes autorizadas</h3>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-4">
                        <input id="searchName" type="search" class="form-control" placeholder="Buscar por nombre">
                    </div>
                    <div class="col-4">
                        <select id="tipoSelect" style="width: max-content" class="form-select"
                            aria-label="Default select example">
                            <option value="">Todos</option>
                            <option value="Vacaciones">Vacaciones</option>
                            <option value="ausencia">Ausencia</option>
                            <option value="permiso_especial">Permisos especiales</option>
                            <option value="incapacidad">Incapacidad</option>
                            <option value="paternidad">Paternidad</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <input id="fechaInput" placeholder="Fecha" type="date" class="form-control"
                            style="width: 2.8rem" />
                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-center">
                            <strong> Total {{ $SumaSolicitudes }} </strong>
                        </div>

                        <div class="d-flex justify-content-center">
                            <span>Colaboradores</span>
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
                            <th scope="col" style="text-align: center; align-content: center">#</th>
                            <th scope="col" style="text-align: center;">Solicitante</th>
                            <th scope="col" style="text-align: center;">Tipo de Solicitud</th>
                            <th scope="col" style="text-align: center;">Dias ausente</th>
                            <th scope="col" style="text-align: center;">Aprobado por (Jefe)</th>
                            <th scope="col" style="text-align: center;">Aprobado por (RH)</th>
                            <th scope="col" style="text-align: center;">Detalles</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($InfoSolicitud as $infoSoli)
                            <tr class="solicitud-row" data-days="{{ implode(',', $infoSoli['days_absent']) }}">
                                <th style="text-align: center; align-content: center;" scope="row">
                                    {{ $infoSoli['id'] }}
                                </th>
                                <td style="text-align: center;">{{ $infoSoli['name'] }}</td>
                                <td style="text-align: center;">{{ $infoSoli['request_type'] }}</td>
                                <td style="text-align: center;">
                                    @foreach ($infoSoli['days_absent'] as $day)
                                        <div>
                                            {{ $day }}
                                        </div>
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @if ($infoSoli['direct_manager_status'] == 'Pendiente')
                                        <span class="badge bg-warning text-dark">{{ $infoSoli['direct_manager_status'] }}
                                        </span>
                                    @elseif ($infoSoli['direct_manager_status'] == 'Aprobada')
                                        <span class="badge bg-success">{{ $infoSoli['direct_manager_status'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">{{ $infoSoli['direct_manager_status'] }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if ($infoSoli['rh_status'] === 'Pendiente')
                                        <span class="badge bg-warning text-dark">{{ $infoSoli['rh_status'] }}</span>
                                    @elseif ($infoSoli['rh_status'] === 'Aprobada')
                                        <span class="badge bg-success">{{ $infoSoli['rh_status'] }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $infoSoli['rh_status'] }}</span>
                                    @endif
                                </td>

                                <td style="text-align: center; cursor: pointer;">
                                    @if ($infoSoli['direct_manager_status'] === 'Pendiente')
                                        <button class="btn btn-link openModalDetails" data-id="{{ $infoSoli['id'] }}"
                                            data-create="{{ $infoSoli['created_at'] }}"
                                            data-name="{{ $infoSoli['name'] }}" data-image="{{ $infoSoli['image'] }}"
                                            data-current_vacation="{{ $infoSoli['current_vacation'] }}"
                                            data-current_vacation_expiration="{{ $infoSoli['current_vacation_expiration'] }}"
                                            data-next_vacation="{{ $infoSoli['next_vacation'] }}"
                                            data-expiration_of_next_vacation="{{ $infoSoli['expiration_of_next_vacation'] }}"
                                            data-direct_manager_status="{{ $infoSoli['direct_manager_status'] }}"
                                            data-rh_status="{{ $infoSoli['rh_status'] }}"
                                            data-request_type="{{ $infoSoli['request_type'] }}"
                                            data-specific_type="{{ $infoSoli['specific_type'] }}"
                                            data-days_absent="{{ implode(',', $infoSoli['days_absent']) }}"
                                            data-method_of_payment="{{ $infoSoli['method_of_payment'] }}"
                                            {{-- data-time="{{ $infoSoli['time'] }}" --}} data-reveal_id="{{ $infoSoli['reveal_id'] }}"
                                            data-file="{{ $infoSoli['file'] }}"> Ver y
                                            autorizar</button>
                                    @else
                                        <button class="btn btn-link openModalDetails" data-id="{{ $infoSoli['id'] }}"
                                            data-create="{{ $infoSoli['created_at'] }}"
                                            data-name="{{ $infoSoli['name'] }}" data-image="{{ $infoSoli['image'] }}"
                                            data-current_vacation="{{ $infoSoli['current_vacation'] }}"
                                            data-current_vacation_expiration="{{ $infoSoli['current_vacation_expiration'] }}"
                                            data-next_vacation="{{ $infoSoli['next_vacation'] }}"
                                            data-expiration_of_next_vacation="{{ $infoSoli['expiration_of_next_vacation'] }}"
                                            data-direct_manager_status="{{ $infoSoli['direct_manager_status'] }}"
                                            data-rh_status="{{ $infoSoli['rh_status'] }}"
                                            data-request_type="{{ $infoSoli['request_type'] }}"
                                            data-specific_type="{{ $infoSoli['specific_type'] }}"
                                            data-days_absent="{{ implode(',', $infoSoli['days_absent']) }}"
                                            data-method_of_payment="{{ $infoSoli['method_of_payment'] }}"
                                            {{-- data-time="{{ $infoSoli['time'] }}" --}} data-reveal_id="{{ $infoSoli['reveal_id'] }}"
                                            data-file="{{ $infoSoli['file'] }}"> Ver
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade bd-example-modal-lg" id="modalDetails" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de la Solicitud</h5>
                        <div id="modalId" style="display: none;"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="miFormularioAprobar" action="authorize/leave/by/direct/boss" method="POST">
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
                        <form id="denyFormRequest" action="reject/leave/by/direct/boss/" method="POST">
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



@section('scripts')
    <script>
        document.getElementById('searchName').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tableAutorizadas tbody tr');

            rows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('tipoSelect').addEventListener('change', function() {
            const tipoValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tableAutorizadas tbody tr');
            rows.forEach(row => {
                const tipo = row.children[2].textContent.toLowerCase();
                if (tipo.includes(tipoValue) || tipoValue === '') {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('fechaInput').addEventListener('input', function() {
            var selectedDate = this.value; // La fecha seleccionada en el formato YYYY-MM-DD
            console.log('selectedDate', selectedDate);
            // Obtén todas las filas de la tabla
            var rows = document.querySelectorAll('.solicitud-row');

            rows.forEach(function(row) {
                // Obtén las fechas asociadas a la fila (separadas por comas)
                var days = row.getAttribute('data-days').split(',');

                // Verifica si la fecha seleccionada está en las fechas de la fila
                if (days.includes(selectedDate) || selectedDate === "") {
                    row.style.display = ''; // Mostrar la fila
                } else {
                    row.style.display = 'none'; // Ocultar la fila
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

                const create = this.getAttribute('data-create');
                const image = this.getAttribute('data-image');


                const date = new Date(create);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate = date.toLocaleDateString('es-ES', options);

                // Asigna el valor formateado a algún elemento en el modal
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


                // Otros atributos
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

                if (direct_manager_status === 'Pendiente') {
                    document.getElementById('buttonModifi').style.display = 'flex';
                    buttonModifi.style.justifyContent = 'end';
                } else {
                    document.getElementById('buttonModifi').style.display = 'none';
                }


                /*Pasar current_vacation_expiration que viene 2026-02-06 a 6 de febrero del 2026*/
                const date2 = new Date(current_vacation_expiration);
                date2.setDate(date2.getDate() + 1);
                const options2 = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                const formattedDate2 = date2.toLocaleDateString('es-ES', options2);

                const date3 = new Date(expiration_of_next_vacation);
                //Sumar 1 día a la fecha de expiración de la siguiente vacación
                date3.setDate(date3.getDate() + 1);
                const options3 = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };

                const formattedDate3 = date3.toLocaleDateString('es-ES', options3);
                document.getElementById('modalId').textContent = id;
                document.getElementById('modalName').textContent = name;
                document.getElementById('modalCurrentVacation').textContent = current_vacation;
                document.getElementById('modalCurrentVacationExpiration').textContent = formattedDate2;
                document.getElementById('modalNextVaca').textContent = next_vacation || 'No hay vacaciones';

                const modalNextVaca = document.getElementById('modalNextVaca');
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
            const form = document.getElementById('miFormularioAprobar');
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

    <style>
        .bg-success {
            background-color: #81C10C !important;
        }

        .bg-warning {
            background-color: #FFC107 !important;
        }
    </style>
@stop
