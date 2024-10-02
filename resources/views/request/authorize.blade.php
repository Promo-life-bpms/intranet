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

        <div class="row">
            <div class="col-4">
                <h3>Solicitudes autorizadas</h3>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-4">
                        <input id="searchName" type="search" class="form-control" placeholder="Buscar por nombre">
                    </div>
                    <div class="col-3">
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
                    <div class="col-3 d-flex align-items-baseline">
                        <input id="fechaInput" type="date" class="form-control mr-1" value="{{ request('fecha') }}" />
                        <i id="clearFilter" class="fas fa-times-circle" style="cursor: pointer;"></i>
                    </div>
                    <div class="col-2">
                        <div class="d-flex justify-content-center">
                            <strong> Total {{ $SumaSolicitudes }} </strong>
                        </div>

                        <div class="d-flex justify-content-center">
                            <span>Solicitudes</span>
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
                            <th scope="col" style="text-align: center;">Tipo de solicitud</th>
                            <th scope="col" style="text-align: center;">Días ausente</th>
                            <th scope="col" style="text-align: center;">Aprobado por (Jefe)</th>
                            <th scope="col" style="text-align: center;">Aprobado por (RH)</th>
                            <th scope="col" style="text-align: center;">Detalles</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (count($solicitudes) === 0)
                            <tr>
                                <td colspan="7" style="text-align: center;">No hay solicitudes</td>
                            </tr>
                        @endif

                        @foreach ($solicitudes as $infoSoli)
                            <tr class="solicitud-row"
                                data-days="{{ isset($infoSoli->days_absent) ? implode(',', $infoSoli->days_absent) : '' }}">
                                <th style="text-align: center; align-content: center;" scope="row">
                                    {{ $infoSoli->id }}
                                </th>
                                <td style="text-align: center;">{{ $infoSoli->name }}</td>
                                <td style="text-align: center;">{{ $infoSoli->request_type }}</td>
                                <td style="text-align: center;">
                                    @foreach ($infoSoli->days_absent as $day)
                                        <div>
                                            {{ $day }}
                                        </div>
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @if ($infoSoli->direct_manager_status == 'Pendiente')
                                        <span class="badge bg-warning text-dark">{{ $infoSoli->direct_manager_status }}
                                        </span>
                                    @elseif ($infoSoli->direct_manager_status == 'Aprobada')
                                        <span class="badge bg-success">{{ $infoSoli->direct_manager_status }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">{{ $infoSoli->direct_manager_status }}
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    @if ($infoSoli->rh_status === 'Pendiente')
                                        <span class="badge bg-warning text-dark">{{ $infoSoli->rh_status }}</span>
                                    @elseif ($infoSoli->rh_status === 'Aprobada')
                                        <span class="badge bg-success">{{ $infoSoli->rh_status }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $infoSoli->rh_status }}</span>
                                    @endif
                                </td>

                                <td style="text-align: center; cursor: pointer;">
                                    @if ($infoSoli->direct_manager_status === 'Pendiente')
                                        <button class="btn btn-link openModalDetails" data-id="{{ $infoSoli->id }}"
                                            data-create="{{ $infoSoli->created_at }}" data-name="{{ $infoSoli->name }}"
                                            data-image="{{ $infoSoli->image }}"
                                            data-current_vacation="{{ $infoSoli->current_vacation }}"
                                            data-current_vacation_expiration="{{ $infoSoli->current_vacation_expiration }}"
                                            data-next_vacation="{{ $infoSoli->next_vacation }}"
                                            data-expiration_of_next_vacation="{{ $infoSoli->expiration_of_next_vacation }}"
                                            data-direct_manager_status="{{ $infoSoli->direct_manager_status }}"
                                            data-rh_status="{{ $infoSoli->rh_status }}"
                                            data-request_type="{{ $infoSoli->request_type }}"
                                            data-specific_type="{{ $infoSoli->specific_type }}"
                                            data-days_absent="{{ implode(',', $infoSoli->days_absent) }}"
                                            data-timeArray="{{ is_Array($infoSoli->time) ? 'true' : 'false' }}"
                                            data-start="{{ $infoSoli->time ? $infoSoli->time[0]['start'] : '12:00' }}"
                                            data-end="{{ $infoSoli->time ? $infoSoli->time[0]['end'] : '12:00' }}"
                                            {{-- Para Ausencia --}}
                                            data-value-type="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['value_type']) ? $infoSoli->more_information[0]['value_type'] : '0' }}"
                                            data-tipo-de-ausencia="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['Tipo_de_ausencia']) ? $infoSoli->more_information[0]['Tipo_de_ausencia'] : '-' }}"
                                            {{-- Para permisos especiales --}}
                                            data-tipo-permiso-especial="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['Tipo_de_permiso_especial']) ? $infoSoli->more_information[0]['Tipo_de_permiso_especial'] : '-' }}"
                                            data-reveal_id="{{ $infoSoli->reveal_id }}"
                                            data-file="{{ $infoSoli->file }}"> Ver y
                                            autorizar</button>
                                    @else
                                        <button class="btn btn-link openModalDetails" data-id="{{ $infoSoli->id }}"
                                            data-create="{{ $infoSoli->created_at }}" data-name="{{ $infoSoli->name }}"
                                            data-image="{{ $infoSoli->image }}"
                                            data-current_vacation="{{ $infoSoli->current_vacation }}"
                                            data-current_vacation_expiration="{{ $infoSoli->current_vacation_expiration }}"
                                            data-next_vacation="{{ $infoSoli->next_vacation }}"
                                            data-expiration_of_next_vacation="{{ $infoSoli->expiration_of_next_vacation }}"
                                            data-direct_manager_status="{{ $infoSoli->direct_manager_status }}"
                                            data-rh_status="{{ $infoSoli->rh_status }}"
                                            data-request_type="{{ $infoSoli->request_type }}"
                                            data-specific_type="{{ $infoSoli->specific_type }}"
                                            data-days_absent="{{ implode(',', $infoSoli->days_absent) }}"
                                            data-timeArray="{{ is_Array($infoSoli->time) ? 'true' : 'false' }}"
                                            data-start="{{ $infoSoli->time ? $infoSoli->time[0]['start'] : '12:00' }}"
                                            data-end="{{ $infoSoli->time ? $infoSoli->time[0]['end'] : '12:00' }}"
                                            {{-- Para Ausencia --}}
                                            data-value-type="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['value_type']) ? $infoSoli->more_information[0]['value_type'] : '0' }}"
                                            data-tipo-de-ausencia="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['Tipo_de_ausencia']) ? $infoSoli->more_information[0]['Tipo_de_ausencia'] : '-' }}"
                                            {{-- Para permisos especiales --}}
                                            data-tipo-permiso-especial="{{ is_array($infoSoli->more_information) && count($infoSoli->more_information) > 0 && isset($infoSoli->more_information[0]['Tipo_de_permiso_especial']) ? $infoSoli->more_information[0]['Tipo_de_permiso_especial'] : '-' }}"
                                            data-reveal_id="{{ $infoSoli->reveal_id }}"
                                            data-file="{{ $infoSoli->file }}"> Ver
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach


                    </tbody>
                </table>
                <div class="d-flex justify-content-end">
                    {{ $solicitudes->appends(request()->input())->links() }}
                </div>
            </div>
        </div>


        <div class="modal fade bd-example-modal-lg" id="modalDetails" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalles de la solicitud</h5>
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
                                        <span>Tienes
                                            <strong id="modalCurrentVacation"></strong>
                                            día(s) disponible(s) que vence(n) el
                                            <strong id="modalCurrentVacationExpiration"></strong>
                                        </span>
                                    </div>

                                    <div class="mt-2" id="secondaryPeriodo">
                                        <span>Tienes
                                            <strong id="modalNextVaca"></strong>
                                            día(s) disponible(s) que vence(n) el
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
                                <div class="col-3 mt-2">
                                    <strong>Tipo </strong>
                                </div>
                                <div class="col-9 mt-2">
                                    <span id="modalRequestType"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Tipo específico </strong>
                                </div>
                                <div class="col-9 mt-2">
                                    <span id="modalSpecificType"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Días ausente </strong>
                                </div>

                                <div class="col-9 mt-2">
                                    <span id="modalDaysAbsent"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Forma de pago </strong>
                                </div>

                                <div class="col-9 mt-2">
                                    <span id="modalMethodOfPayment"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Tiempo </strong>
                                </div>

                                <div class="col-9 mt-2" id="timeStatus">
                                    <span>Tiempo completo</span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Apoyo </strong>
                                </div>

                                <div class="col-9 mt-2">
                                    <span id="modalRevealId"></span>
                                </div>

                                <div class="col-3 mt-2">
                                    <strong>Justificante </strong>
                                </div>

                                <div class="col-9 mt-2" id="viewFile">
                                    {{-- <a id="file" href="" target="_blank">Ver archivo</a> --}}
                                </div>
                            </div>

                            <div id="buttonModifi" style="display: none">
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

                        <button id='closeModalDeny' type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="denyFormRequest" action="reject/leave/by/direct/boss/" method="POST">
                            @csrf
                            <textarea style="min-width: 100%" placeholder="Motivo" class="form-control" id="commentary" name="commentary"
                                required></textarea>
                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary" id="denyButtonForm">Enviar</button>
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
        /*Limpiar los campos del formulario de rechazo*/
        document.getElementById('closeModalDeny').addEventListener('click', function() {
            const form = document.getElementById('denyFormRequest');
            form.reset();
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
                const method_of_payment = (request_type === 'Vacaciones' || request_type === 'vacaciones') ?
                    'A cuenta de vacaciones' :
                    request_type === 'Ausencia' ?
                    'Descontar Tiempo/Día' :
                    (request_type === 'Paternidad' || request_type === 'Permisos especiales') ?
                    'Permiso Especial' :
                    request_type === 'Incapacidad' ?
                    'Pago del IMSS' :
                    'Sin especificar';
                const time = this.getAttribute('data-time');
                const reveal_id = this.getAttribute('data-reveal_id');
                const file = this.getAttribute('data-file');

                console.log('file', file);

                if (file === 'No hay justificante' || file === '' || file === null || file === 'null' ||
                    file === undefined) {
                    console.log('no hay justificante');
                } else {
                    console.log('si hay justificante');
                }

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

                const timeText = this.getAttribute('data-timeArray')
                const dataStar = this.getAttribute('data-start'); // Obtener la hora de salida
                const dataEnd = this.getAttribute('data-end'); // Obtener la hora de regreso
                /*Para Ausencia*/
                const valueType = this.getAttribute('data-value-type');
                const tipoDeAusencia = this.getAttribute('data-tipo-de-ausencia');

                /*Para permisos especiales*/
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
                } else if (direct_manager_status === 'Cancelada por el usuario') {
                    modalDirectManagerStatus.textContent = 'Cancelada por el usuario';
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
                } else if (direct_manager_status === 'Cancelada por el usuario') {
                    modalRhStatus.textContent = 'Cancelada por el usuario';
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
            document.getElementById('loadingSpinner').style.display = 'flex';
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


        // document.getElementById('denyButtonForm').addEventListener('click', function() {
        //     document.getElementById('loadingSpinner').style.display = 'flex';

        //     const form = document.getElementById('denyFormRequest');
        //     /*Mandar el ID de la solicitus que se va a aprobar del que viene en el modal*/
        //     const id = document.getElementById('modalId').textContent;
        //     const inputId = document.createElement('input');
        //     inputId.type = 'hidden';
        //     inputId.name = 'id';
        //     inputId.value = id;
        //     form.appendChild(inputId);

        //     form.submit();
        // });

        const formDeny = document.getElementById('denyFormRequest');

        formDeny.addEventListener('submit', function(event) {
            event.preventDefault();

            document.getElementById('loadingSpinner').style.display = 'flex';
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


        document.getElementById('denyRequest').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            $('#modalDeny').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
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




        const urlParams = new URLSearchParams(window.location.search);
        const search = urlParams.get('search');
        if (search) {
            document.getElementById('searchName').value = search;
        }
    </script>

    <style>
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
            color: #81C10C !important;
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
