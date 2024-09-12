@extends('layouts.app')
@section('content')
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <div>
        {{-- Because she competes with no one, no one can compete with her. --}}

        <div class="d-flex justify-content-between">
            <h3 class="mb-4">Permisos y Vacaciones</h3>

            <div>
                <span>
                    Dias de vacaciones disponibles:
                    <strong>{{ $diasdisponibles }}</strong>
                </span>
            </div>
        </div>
        @php
            use Carbon\Carbon;
            Carbon::setLocale('es');
            $fecha_expiracion_actual = Carbon::parse($fecha_expiracion_actual)
                ->locale('es')
                ->translatedFormat('d \d\e F \d\e Y');

            if ($fecha_expiracion_entrante != null) {
                $fecha_expiracion_entrante = Carbon::parse($fecha_expiracion_entrante)
                    ->locale('es')
                    ->translatedFormat('d \d\e F \d\e Y');
            }
        @endphp

        <div class="d-flex mb-4">
            <div id="openModalVacaciones" style="width: 18%;">
                <div class="tarjeta hover-tarjeta" style="border-bottom: 10px solid var(--color-target-1);">
                    <div class="d-flex justify-content-end ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                            style="color:  var(--color-target-1);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </div>
                    <div>
                        <strong style="color:  var(--color-target-1);">Vacaciones</strong>
                    </div>
                </div>
            </div>

            <div id="openModalAusencia" style="width: 18%; margin-left: 20px">
                <div class="tarjeta hover-tarjeta" style="border-bottom: 10px solid var(--color-target-2);">
                    <div class="d-flex justify-content-end ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                            style="color:  var(--color-target-2);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div>
                        <strong style="color:  var(--color-target-2);">Ausencia</strong>
                    </div>
                </div>
            </div>


            <div id="openModalPaternidad" style="width: 18%; margin-left: 20px">
                <div class="tarjeta hover-tarjeta" style="border-bottom: 10px solid var(--color-target-5);">
                    <div class="d-flex justify-content-end ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                            viewBox="0 0 24 24" stroke-width="0.2" stroke="currentColor" class="size-6"
                            style="color:  white">
                            <path
                                d="M18.4832 15.0004C19.4722 13.5203 20 11.7803 20 10.0002V8.23631C20 7.85754 20.214 7.51128 20.5528 7.34189L21.4472 6.89467C21.9412 6.64768 22.1414 6.04701 21.8944 5.55303C21.6474 5.05905 21.0468 4.85883 20.5528 5.10582L19.6584 5.55303C18.642 6.06121 18 7.1 18 8.23631V9.00024L12 9.00024V2.00024C12 1.44796 11.5523 1.00024 11 1.00024C9.21996 1.00024 7.47991 1.52808 5.99986 2.51702C4.51982 3.50595 3.36627 4.91156 2.68508 6.55609C2.00389 8.20063 1.82566 10.0102 2.17293 11.7561C2.5202 13.5019 3.37736 15.1055 4.63604 16.3642C4.88278 16.611 5.14279 16.8423 5.41453 17.0573C4.03795 17.3297 3 18.5437 3 20.0002C3 21.6571 4.34314 23.0002 6 23.0002C7.65685 23.0002 9 21.6571 9 20.0002C9 19.5356 8.89434 19.0955 8.70575 18.7029C8.88354 18.7498 9.0631 18.7913 9.24418 18.8273C10.5913 19.0953 11.9763 19.0504 13.2942 18.7029C13.1057 19.0955 13 19.5356 13 20.0002C13 21.6571 14.3431 23.0002 16 23.0002C17.6569 23.0002 19 21.6571 19 20.0002C19 18.5437 17.962 17.3297 16.5855 17.0573C17.3175 16.4779 17.9589 15.785 18.4832 15.0004ZM16.7834 13.8646C17.3611 13.0001 17.7352 12.0209 17.8834 11.0002L4.11656 11.0002C4.13389 11.1195 4.15435 11.2386 4.17795 11.3572C4.44634 12.7065 5.10881 13.9459 6.08158 14.9187C7.05435 15.8914 8.29374 16.5539 9.64301 16.8223C10.9923 17.0907 12.3908 16.9529 13.6618 16.4265C14.9328 15.9 16.0191 15.0085 16.7834 13.8646ZM10 3.1168V9.00024L4.11656 9.00024C4.19904 8.43247 4.35188 7.87411 4.57377 7.33842C5.10023 6.06743 5.99176 4.9811 7.13562 4.2168C8.0001 3.63917 8.97936 3.26508 10 3.1168ZM6 21.0079C5.44349 21.0079 4.99235 20.5568 4.99235 20.0002C4.99235 19.4437 5.44349 18.9926 6 18.9926C6.5565 18.9926 7.00764 19.4437 7.00764 20.0002C7.00764 20.5568 6.5565 21.0079 6 21.0079ZM14.9924 20.0002C14.9924 20.5568 15.4435 21.0079 16 21.0079C16.5565 21.0079 17.0076 20.5568 17.0076 20.0002C17.0076 19.4437 16.5565 18.9926 16 18.9926C15.4435 18.9926 14.9924 19.4437 14.9924 20.0002Z"
                                fill-rule="evenodd" clip-rule="evenodd" fill="#C10C8E" stroke="currentColor" />
                        </svg>
                    </div>
                    <div>
                        <strong style="color:  var(--color-target-5);">Paternidad</strong>
                    </div>
                </div>
            </div>

            <div id="openModalIncapacidad" style="width: 18%; margin-left: 20px">
                <div class="tarjeta" style="border-bottom: 10px solid var(--color-target-4);">
                    <div class="d-flex justify-content-end ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                            style="color:  var(--color-target-4);">
                            <path d="M13.6667 16H10.3333V13.6667H8V10.3333H10.3333V8H13.6667V10.3333H16V13.6667H13.6667V16Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M5 18L3.13036 4.91253C3.05646 4.39524 3.39389 3.91247 3.90398 3.79912L11.5661 2.09641C11.8519 2.03291 12.1481 2.03291 12.4339 2.09641L20.096 3.79912C20.6061 3.91247 20.9435 4.39524 20.8696 4.91252L19 18C18.9293 18.495 18.5 21.5 12 21.5C5.5 21.5 5.07071 18.495 5 18Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>

                    </div>
                    <div>
                        <strong style="color:  var(--color-target-4);">Incapacidad</strong>
                    </div>
                </div>
            </div>

            <div id="openModalPermisoEspecial" style="width: 22%; margin-left: 20px">
                <div class="tarjeta" style="border-bottom: 10px solid var(--color-target-3);">
                    <div class="d-flex justify-content-end ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                            style="color:  var(--color-target-3);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    </div>
                    <div>
                        <strong style="color:  var(--color-target-3);">Permisos epeciales</strong>
                    </div>
                </div>
            </div>

        </div>


        <div class="d-flex">
            <!-- Columna izquierda -->
            <div style="width: 100%;">
                <div class="card-seccion" style="height: 100vh;">
                    <div class="d-flex justify-content-between">
                        <strong>Mis solicitudes</strong>
                        <div class="d-flex">
                            <select id="tipoSelect" style="width: max-content" class="form-select mr-1"
                                aria-label="Default select example">
                                <option value="">Todos</option>
                                <option value="Vacaciones">Vacaciones</option>
                                <option value="ausencia">Ausencia</option>
                                <option value="permiso_especial">Permisos especiales</option>
                                <option value="incapacidad">Incapacidad</option>
                                <option value="paternidad">Paternidad</option>
                            </select>


                            <div class=" d-flex align-items-center justify-content-center align-content-center mr-1 ">
                                <span>Status RH: </span>
                            </div>

                            <select id="selectRh" style="width: max-content" class="form-select mr-1"
                                aria-label="Default select example">
                                <option value="">Todas</option>
                                <option value="aprobadas">Aprobadas</option>
                                <option value="Pendiente">Pendientes</option>
                                <option value="rechazadas">Rechazadas</option>
                            </select>

                            <input id="fechaInput" placeholder="Fecha" type="date" class="form-control"
                                style="width: 10rem" />

                        </div>
                    </div>
                    <div class="mt-4">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="text-align: center;">#</th>
                                    <th scope="col" style="text-align: center;">Tipo</th>
                                    <th scope="col" style="text-align: center;">Fechas de ausencia</th>
                                    <th scope="col" style="text-align: center;">Tiempo</th>
                                    <th scope="col" style="text-align: center;">Jefe directo</th>
                                    <th scope="col" style="text-align: center;">RH</th>
                                    <th scope="col" style="text-align: center;">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Si no tengo solicitudes que aparezca un mensaje de no tienes solicitude --}}
                                @if (count($solicitudes) == 0)
                                    <tr>
                                        <td colspan="7" style="text-align: center;">No tienes solicitudes</td>
                                    </tr>
                                @endif
                                @foreach ($solicitudes as $solicitud)
                                    <tr class="solicitud-row" data-tipo="{{ $solicitud->tipo }}"
                                        data-statusRh="{{ $solicitud->rh_status }}"
                                        data-days="{{ implode(',', $solicitud->days) }}">
                                        <th scope="row">{{ $solicitud->id_request }}</th>
                                        <td style="text-align: center;">{{ $solicitud->tipo }}</td>
                                        <td style="text-align: center;">
                                            @foreach ($solicitud->days as $day)
                                                <div>
                                                    {{ $day }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td style="text-align: center;">Tiempo completo</td>
                                        <td style="text-align: center;">
                                            @if ($solicitud->direct_manager_status == 'Pendiente')
                                                <span
                                                    class="badge bg-warning text-dark">{{ $solicitud->direct_manager_status }}</span>
                                            @elseif ($solicitud->direct_manager_status == 'Aprobada')
                                                <span
                                                    class="badge bg-success">{{ $solicitud->direct_manager_status }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ $solicitud->direct_manager_status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if ($solicitud->rh_status == 'Pendiente')
                                                <span
                                                    class="badge bg-warning text-dark">{{ $solicitud->rh_status }}</span>
                                            @elseif ($solicitud->rh_status == 'Aprobada')
                                                <span class="badge bg-success">{{ $solicitud->rh_status }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $solicitud->rh_status }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; cursor: pointer;">
                                            <button class="btn btn-link openModalBtn"
                                                data-id="{{ $solicitud->id_request }}"
                                                data-tipo="{{ $solicitud->tipo }}"
                                                data-details="{{ $solicitud->details }}"
                                                data-reveal_id="{{ $solicitud->reveal_id }}"
                                                data-direct_manager_id="{{ $solicitud->direct_manager_id }}"
                                                data-direct_manager_status="{{ $solicitud->direct_manager_status }}"
                                                data-statusRh="{{ $solicitud->rh_status }}"
                                                data-file="{{ $solicitud->file }}"
                                                data-days="{{ implode(',', $solicitud->days) }}">
                                                Ver
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div style="width: 28%; margin-left: 25px; display: flex; flex-direction: column;">
                <div class="card-seccion">
                    <div>
                        <strong>Nota</strong>
                    </div>
                    <div class="mt-2 mb-1 text-align: justify;">
                        <span>
                            Te queda
                            <strong>{{ $vacaciones_actuales }}</strong>
                            día disponible que vence el
                            <strong>{{ $fecha_expiracion_actual }}</strong>.
                        </span>
                    </div>


                    @if ($fecha_expiracion_entrante != null)
                        <div class="mt-2 mb-1 text-align: justify;">
                            <span>
                                Tienes
                                <strong>{{ $vacaciones_entrantes }}</strong>
                                días disponibles que vencen el
                                <strong>{{ $fecha_expiracion_entrante }}</strong>.
                            </span>
                        </div>
                    @endif

                    <div id="openModalCalendario" class="d-flex justify-content-center mt-3" style="cursor: pointer">
                        <strong>Ver mi calendario</strong>
                    </div>
                </div>

                <!-- Segunda tarjeta -->
                <div class="card-seccion mt-4">
                    <div style="margin-right: 5px;">
                        <strong>Calendario General</strong>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-center align-content-center">
                            <strong>
                                Vacaciones
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span>
                                    Utilizado:
                                </span>
                            </div>
                            <div>
                                <strong>{{ $porcentajetomadas }}%</strong>
                            </div>
                        </div>

                        <div class="progress mt-1">
                            <div class="progress-bar" role="progressbar"
                                style="width: {{ $porcentajetomadas }}%; background-color: var(--color-target-1);"
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-center align-content-center">
                            <strong>
                                P. Especiales
                            </strong>
                        </div>

                        <div class="d-flex justify-content-between">
                            <div>
                                <span>
                                    Utilizado:
                                </span>
                            </div>
                            <div>
                                <strong>50%</strong>
                            </div>
                        </div>

                        <div class="progress mt-1">
                            <div class="progress-bar" role="progressbar"
                                style="width: 50%; background-color: var(--color-target-3);" aria-valuenow="25"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- Modal calendario -->
        <div class="modal fade bd-example-modal-lg" id="modalCalendario" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Calendario</h5>
                        <button id="closeModalCalendario" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div style="max-height: 400px">
                                <div class="mt-3" id='calendarioDays'></div>
                            </div>
                            <div class="row justify-content-start">
                                <div class="col-4 d-flex">
                                    <div class="mr-1 col-4"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <span> Vacaciones</span>
                                    </div>
                                    <div class="mr-1 col-7" style="display: flex; align-items: center;">
                                        <div
                                            style="border: 3px solid var(--color-target-1); background-color: var(--color-target-1); width: 30%; height: 25%; border-radius: 10px; ">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 d-flex">
                                    <div class="mr-1 col-5"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <span>P. Especiales</span>
                                    </div>
                                    <div class="mr-1 col-7" style="display: flex; align-items: center;">
                                        <div
                                            style="border: 3px solid var(--color-target-3); background-color: var(--color-target-3); width: 30%; height: 25%; border-radius: 10px; ">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 d-flex">
                                    <div class="mr-1 col-4"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <span>Paternidad</span>
                                    </div>
                                    <div class="mr-1 col-7" style="display: flex; align-items: center;">
                                        <div
                                            style="border: 3px solid var(--color-target-5); background-color: var(--color-target-5); width: 30%; height: 25%; border-radius: 10px; ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-start mt-3">
                                <div class="col-4 d-flex">
                                    <div class="mr-1 col-4"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <span>Ausencia</span>
                                    </div>
                                    <div class="mr-1 col-7" style="display: flex; align-items: center;">
                                        <div
                                            style="border: 3px solid var(--color-target-2); background-color: var(--color-target-2); width: 30%; height: 25%; border-radius: 10px; ">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4 d-flex">
                                    <div class="mr-1 col-5"
                                        style="display: flex; justify-content: center; align-items: center;">
                                        <span>Incapacidad</span>
                                    </div>
                                    <div class="mr-1 col-7" style="display: flex; align-items: center;">
                                        <div
                                            style="border: 3px solid var(--color-target-4); background-color: var(--color-target-4); width: 30%; height: 25%; border-radius: 10px; ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal permiso de días -->
        <div class="modal fade bd-example-modal-lg" id="modaTarjetas" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Título del Modal</h5>
                        <button id="closemodal" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="miFormulario" action="create/vacation/or/leave/request" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="mt-2" id="dynamicContentEncabezado">
                                <!-- Aquí se va a cambiar el contenido según la tarjeta seleccionada -->
                            </div>

                            <div class="d-flex justify-content-between">
                                <div class="mr-2">
                                    {{-- <span class="mt-2">Selecciona los días de ausencia</span> --}}

                                    {{-- <span id="textDinamicCalendar"></span> --}}

                                    <div class="mt-2" id="textDinamicCalendar">
                                        <!-- Aquí se va a cambiar el contenido según la tarjeta seleccionada -->
                                    </div>

                                    <div class="mt-2" id='calendario'></div>
                                </div>
                                <div class="mt-5">
                                    <div class="mt-2" id="dynamicContentFormaPago">
                                        <!-- Aquí se va a cambiar el contenido según la tarjeta seleccionada -->
                                    </div>

                                    <div class="mt-3">
                                        <span>Anexa tu justificación (opcional)</span>
                                        <div class="mt-1">
                                            <input type="file" class="form-control-file" id="archivos"
                                                name="archivos">
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <span>¿Quien atenderá tus pendientes?</span>
                                        <select class="form-select mt-1" aria-label="Default select example"
                                            id='reveal_id' name="reveal_id" required>
                                            @foreach ($users as $user)
                                                <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-3 ">
                                        <button style="width: 100%" type="submit"
                                            class="btn btn-primary">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal ver solicitud -->
        <div class="modal fade bd-example-modal-lg" id="verSolivitud" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Mi solicitud</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-bo-dy">
                        {{-- ID de solicitud: <span id="modalSolicitudId"></span> --}}
                        <div class="container">
                            <div class="row mt-2">
                                <div class="col-6">
                                    <strong> Tipo de solicitud:</strong>
                                </div>
                                <div class="col-5">
                                    <span id="tipo"></span>
                                </div>

                                <div id="editSolcitud" class="col-1 d-flex align-content-center justify-content-center "
                                    style="border-radius: 25px; background-color: #E58D22; height: 45px; width: 45px; align-items: center; cursor: pointer;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="32" height="32"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"
                                        style="color: white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-6">
                                    <strong> Tipo de solicitud especifica:</strong>
                                </div>
                                <div class="col-6">
                                    -
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong> Forma de pago:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="method-of-payment"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong> Fechas de ausencia:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="days"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong>Tiempo de ausencia:</strong>
                                </div>
                                <div class="col-6">
                                    Tiempo completo
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong> Justificante:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="file"></span>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <strong>Responsable de sus asuntos:</strong>
                                </div>
                                <div class="col-6">
                                    <span id="reveal_id_name"></span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <strong>Motivo:</strong>
                                <textarea style="min-width: 100%" class="form-control" id="details_text" name="details_text" disabled></textarea>
                            </div>

                            <div class="d-flex mt-3">
                                <div class="mr-3">
                                    <div>
                                        <span>JEFE DIRECTO</span>

                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <span style="padding: 8px 15px;" class="badge"
                                            id="direct_manager_status"></span>
                                    </div>
                                </div>
                                <div>
                                    <div>
                                        <span>RECURSOS HUMANOS</span>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <span style="padding: 8px 15px;" class="badge" id="statusRh"> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <div>
                                    <button class="btn btn-danger mb-2">Cancelar Solicitud</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>





    </div>



    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <script>
        selectedDays = [];

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('openModalCalendario').addEventListener('click', function() {
                $('#modalCalendario').modal('show');
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('closeModalCalendario').addEventListener('click', function() {
                $('#modalCalendario').modal('hide');
            });
        });

        document.getElementById('closemodal').addEventListener('click', function() {
            $('#modaTarjetas').modal('hide');
            var calendar = new FullCalendar.Calendar(document.getElementById('calendario'), {
                // Configura tu calendario aquí (eventos, opciones, etc.)
                selectable: true, // Habilita la selección de días
                // Otros parámetros que necesites
            });
            $('#modaTarjetas').on('hidden.bs.modal', function() {
                if (calendario) {
                    calendar.getEventSources().forEach(eventSource => eventSource.remove());
                    // También puedes borrar cualquier otra variable o estado relacionado si es necesario
                    selectedDays = []; // Si tienes una lista de días seleccionados
                    ///Tambien quiero que se resetee el formulario
                    document.getElementById('miFormulario').reset();

                } else {
                    console.log('No hay calendario para destruir');
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            $('#modaTarjetas').on('shown.bs.modal', function() {
                var selectedRanges = [];
                var calendarEl = document.getElementById('calendario');
                var calendario = new FullCalendar.Calendar(calendarEl, {
                    locale: "es",
                    hiddenDays: [0, 6],
                    selectable: true,
                    dateClick: function(info) {
                        var selectedDate = info.dateStr;
                        var dayNumberEl = info.dayEl.querySelector(
                            '.fc-daygrid-day-number');
                        if (!selectedDays.includes(selectedDate)) {
                            selectedDays.push(selectedDate);
                            if (dayNumberEl) {
                                dayNumberEl.classList.add('fc-day-selected');
                            } else {
                                console.log('no encontrada')
                            }
                        } else {
                            selectedDays = selectedDays.filter(day => day !==
                                selectedDate);

                            if (dayNumberEl) {
                                dayNumberEl.classList.remove('fc-day-selected');
                            }
                            // info.dayEl.classList.remove('fc-day-selected');
                            // console.log('Días seleccionados:', selectedDays);
                        }
                    },
                    datesSet: function() {
                        // Después de que se cambia la vista del calendario, volvemos a aplicar las clases CSS
                        var days = document.querySelectorAll('.fc-daygrid-day');
                        days.forEach(function(day) {
                            var date = day.getAttribute('data-date');
                            var dayNumberEl = day.querySelector(
                                '.fc-daygrid-day-number');

                            // Primero, asegurarse de que el número del día tenga la clase eliminada antes de volver a aplicarla
                            if (dayNumberEl) {
                                dayNumberEl.classList.remove('fc-day-selected');
                            }

                            if (selectedDays.includes(date)) {
                                if (dayNumberEl) {
                                    dayNumberEl.classList.add('fc-day-selected');
                                }
                            }
                        });
                    },


                    validRange: function(nowDate) {
                        return {
                            start: nowDate // no permite seleccionar días antes de hoy
                        };
                    },

                    //MOSTRAR LOS BOTONES DE MES, SEMANA Y LISTA//
                    headerToolbar: {
                        left: '',
                        center: 'prev,title,next',
                        right: '',
                    },
                });
                calendario.render();
            });

            /* Filtrar solicitudes con respecto al select de tipo */
            document.getElementById('tipoSelect').addEventListener('change', function() {
                var selectedTipo = this.value;
                // Obtén todas las filas de la tabla
                var rows = document.querySelectorAll('.solicitud-row');

                rows.forEach(function(row) {
                    // Verifica si el tipo de la fila coincide con la selección
                    if (selectedTipo === "" || row.getAttribute('data-tipo') === selectedTipo) {
                        row.style.display = ''; // Mostrar la fila
                    } else {
                        row.style.display = 'none'; // Ocultar la fila
                    }
                });
            });

            /* Filtrar solicitudes con respecto al select del estatus de RH */
            document.getElementById('selectRh').addEventListener('change', function() {
                var selectedStatusRh = this.value;
                console.log('selectedStatusRh', selectedStatusRh);
                // Obtén todas las filas de la tabla
                var rows = document.querySelectorAll('.solicitud-row');
                console.log('rows', rows);
                rows.forEach(function(row) {
                    // Verifica si el tipo de la fila coincide con la selección
                    if (selectedStatusRh === "" || row.getAttribute('data-statusRh') ===
                        selectedStatusRh) {
                        row.style.display = ''; // Mostrar la fila
                    } else {
                        row.style.display = 'none'; // Ocultar la fila
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


            // Poner dias de vacaciones y permisos especiales en el calendario

            $('#modalCalendario').on('shown.bs.modal', function() {

                let dayDefaultCalendar = {
                    vacaciones: ['2024-09-09', '2024-09-17'],
                    permisos: ['2024-09-12', '2024-09-13']
                };

                console.log('dayDefaultCalendar', dayDefaultCalendar);


                var selectedRanges = [];
                var calendarEl = document.getElementById('calendarioDays');
                ///Seleccionar el div donde viene contenido el dia


                var calendario = new FullCalendar.Calendar(calendarEl, {
                    locale: "es",
                    hiddenDays: [0, 6],
                    selectable: true,


                    dayCellDidMount: function(info) {
                        // Accede al <div> con la clase fc-daygrid-day-top
                        var dayTopElement = info.el.querySelector(
                            '.fc-daygrid-day-top');

                        if (dayTopElement) {
                            // Añade la clase personalizada
                            dayTopElement.classList.add('custom-day-top-class');
                        }



                        var dateStr = info.date.toISOString().split('T')[
                            0]; // Formato 'YYYY-MM-DD'
                        var daysVacaciones = dayDefaultCalendar?.vacaciones
                        var daysPermisos = dayDefaultCalendar?.permisos

                        if (daysVacaciones.includes(dateStr)) {
                            var dayNumberElement = info.el.querySelector(
                                '.fc-daygrid-day-number'
                            ); // Selecciona el <a> dentro del <td>
                            if (dayNumberElement) {
                                dayNumberElement.classList.add(
                                    'highlighted-day-vacaciones'
                                ); // Añade tu clase personalizada
                            }
                        }


                        if (daysVacaciones.includes(dateStr)) {
                            var dayNumberElement = info.el.querySelector(
                                '.fc-daygrid-day-number'
                            ); // Selecciona el <a> dentro del <td>
                            if (dayNumberElement) {
                                dayNumberElement.classList.add(
                                    'highlighted-day-vacaciones'
                                ); // Añade tu clase personalizada
                            }
                        }

                        if (daysPermisos.includes(dateStr)) {
                            var dayNumberElement = info.el.querySelector(
                                '.fc-daygrid-day-number'
                            ); // Selecciona el <a> dentro del <td>
                            if (dayNumberElement) {
                                dayNumberElement.classList.add(
                                    'highlighted-day-permisos'
                                ); // Añade tu clase personalizada
                            }
                        }
                    },

                    //MOSTRAR LOS BOTONES DE MES, SEMANA Y LISTA//
                    headerToolbar: {
                        left: '',
                        center: 'prev,title,next',
                        right: '',
                    },
                });
                calendario.render();
            });

        })

        // Evento cuando se hace clic en el botón para abrir el modal
        document.querySelectorAll('.openModalBtn').forEach(button => {
            button.addEventListener('click', function() {
                const solicitudId = this.getAttribute('data-id');
                const tipo = this.getAttribute('data-tipo');
                const methodOfPayment = tipo === 'Vacaciones' ?
                    'A cuenta de vacaciones' :
                    'A cuenta de permisos especiales';
                const details = this.getAttribute('data-details');
                const revealId = this.getAttribute('data-reveal_id');
                const directManagerId = this.getAttribute('data-direct_manager_id');
                const directManagerStatus = this.getAttribute('data-direct_manager_status');
                const statusRh = this.getAttribute('data-statusRh');
                const file = this.getAttribute('data-file');
                const days = this.getAttribute('data-days');

                // Cambiar el contenido del modal
                document.getElementById('tipo').textContent = tipo;
                document.getElementById('method-of-payment').textContent = methodOfPayment;
                document.getElementById('details_text').textContent = details;
                document.getElementById('reveal_id_name').textContent = revealId;
                // document.getElementById('direct_manager_id') = directManagerId;
                document.getElementById('direct_manager_status').textContent = directManagerStatus;
                document.getElementById('statusRh').textContent = statusRh;
                document.getElementById('file').textContent = file;
                document.getElementById('days').textContent = days;

                var statusManegerElement = document.getElementById('direct_manager_status');
                var statusRhElement = document.getElementById('statusRh');

                // Supongamos que 'direct_manager_status' tiene el valor que quieres evaluar
                if (directManagerStatus === 'Aprobada') {
                    console.log('Aprobada');
                    // Remueve cualquier clase anterior y añade la clase 'bg-success'
                    statusManegerElement.classList.remove('bg-warning', 'text-dark');
                    statusManegerElement.classList.add('bg-success'); // Se añade 'bg-success'
                } else if (directManagerStatus === 'Pendiente') {
                    statusManegerElement.classList.remove('bg-success');
                    statusManegerElement.classList.remove('badge', 'bg-danger');
                    statusManegerElement.classList.add('bg-warning', 'text-dark');
                } else {
                    statusManegerElement.classList.remove('bg-success');
                    statusManegerElement.classList.remove('bg-warning', 'text-dark');
                    statusManegerElement.classList.add('badge', 'bg-danger');
                }


                if (statusRh === 'Aprobada') {
                    // Remueve cualquier clase anterior y añade la clase 'bg-success'
                    statusRhElement.classList.remove('bg-warning', 'text-dark');
                    statusRhElement.classList.add('bg-success'); // Se añade 'bg-success'
                } else if (statusRh === 'Pendiente') {
                    statusRhElement.classList.remove('bg-success');
                    statusRhElement.classList.remove('badge', 'bg-danger');
                    statusRhElement.classList.add('bg-warning', 'text-dark');
                } else {
                    // Remueve 'bg-success' y añade otra clase, por ejemplo 'bg-danger'
                    statusRhElement.classList.remove('bg-success');
                    statusRhElement.classList.remove('bg-warning', 'text-dark');
                    statusRhElement.classList.add('badge', 'bg-danger');
                }
                // Muestra el modal (debes tenerlo en tu HTML)
                const modal = new bootstrap.Modal(document.getElementById('verSolivitud'));
                modal.show();
            });
        });

        // /* Abrir modal de editar solicitud */
        document.getElementById('editSolcitud').addEventListener('click', function() {
            // Cambia el título del modal
            $('#verSolivitud').modal('hide');

            document.getElementById('modalTitle').innerText = 'Editar solicitud';
            // Cambia el contenido dinámico
            document.getElementById('dynamicContentEncabezado').innerHTML = `
        <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
    `;

            document.getElementById('dynamicContentFormaPago').innerHTML = `
        <span>Forma de pago (Asignación automatica)</span>
        <input type="text" disabled class="form-control mt-1"
        value="A cuenta de vacaciones" id="forma_pago" name="forma_pago" disabled>
    `;
            // Abre el modal
            $('#modaTarjetas').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show'); // Muestra el modal
        });


        document.getElementById('openModalVacaciones').addEventListener('click', function() {
            // Cambia el título del modal
            document.getElementById('modalTitle').innerText = 'Vacaciones';
            // Cambia el contenido dinámico
            document.getElementById('dynamicContentEncabezado').innerHTML = `
            <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
        `;

            document.getElementById('textDinamicCalendar').innerHTML = `
            <span>Selecciona los días de vacaciones</span>
        `;

            document.getElementById('dynamicContentFormaPago').innerHTML = `
            <span>Forma de pago (Asignación automatica)</span>
            <input type="text" disabled class="form-control mt-1"
            value="A cuenta de vacaciones" id="forma_pago" name="forma_pago" disabled>
        `;
            // Abre el modal
            $('#modaTarjetas').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show'); // Muestra el modal
        });

        document.getElementById('openModalAusencia').addEventListener('click', function() {
            // Cambia el título del modal
            document.getElementById('modalTitle').innerText = 'Ausencia';
            document.getElementById('textDinamicCalendar').innerHTML = `
            <span>Selecciona el día que no te presentarás</span>
            `;

            document.getElementById('dynamicContentEncabezado').innerHTML = `
           <div class="d-flex">
                <div class="mr-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ausenciaTipo" id="retardo" value="retardo">
                        <label class="form-check-label" for="retardo">Retardo</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ausenciaTipo" id="salida_antes" value="salida_antes">
                        <label class="form-check-label" for="salida_antes">Salida antes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ausenciaTipo" id="salida_durante" value="salida_durante">
                        <label class="form-check-label" for="salida_durante">Salida durante</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="ausenciaTipo" id="falta_trabajo" value="falta_trabajo">
                        <label class="form-check-label" for="falta_trabajo">Falta de trabajo</label>
                    </div>
                </div>

                <div style="width: 75.5%">
                    <span id="text-time" class="d-none" style="color: red">
                        Toma en cuenta que si la ausencia supera las 4 horas, se descontará de los días de vacaciones.
                    </span>

                    <div class="d-flex justify-content-between">
                        <div id="horaSalida" class="cs-form d-none mb-3">
                            <span>Hora de salida: </span>
                            <input placeholder="Hora de salida" type="time" class="form-control" name="hora_salida" />
                        </div>

                        <div id="horaEntrada" class="cs-form d-none mb-3">
                            <span>Hora de regreso: </span>
                            <input placeholder="Hora de regreso" type="time" class="form-control" name="hora_regreso" />
                        </div>
                    </div>

                    <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
                </div>
            </div>
        `;

            // Cambia el contenido dinámico
            document.getElementById('dynamicContentFormaPago').innerHTML = `
            <div class="mb-2">
                <span>Forma de pago (Asignación automatica)</span>
                <input type="text" disabled class="form-control mt-1"
                value="Descontar Tiempo/Día" id="forma_pago" name="forma_pago" disabled>
            </div>
        `;
            // Abre el modal
            $('#modaTarjetas').modal('show');
            $(document).ready(function() {
                $('input[name="ausenciaTipo"]').on('change', function() {
                    ausenciaTipo($(this).attr('id'));
                    console.log('value', $(this).attr('id'));
                });
            });
            const textTime = document.querySelector('#text-time');
            const horaSalida = document.querySelector('#horaSalida');
            const horaEntrada = document.querySelector('#horaEntrada');

            function ausenciaTipo(value) {
                console.log('value', value);
                switch (value) {
                    case 'salida_antes':
                        horaSalida.classList.remove('d-none');
                        horaEntrada.classList.add('d-none');
                        textTime.classList.add('d-none');
                        break;
                    case 'salida_durante':
                        horaEntrada.classList.remove('d-none');
                        horaSalida.classList.remove('d-none');
                        textTime.classList.remove('d-none');
                        break;
                    default:
                        horaSalida.classList.add('d-none');
                        horaEntrada.classList.add('d-none');
                        textTime.classList.add('d-none');
                }
            }


        });


        document.getElementById('openModalPaternidad').addEventListener('click', function() {
            // Cambia el título del modal
            document.getElementById('modalTitle').innerText = 'Paternidad';
            document.getElementById('dynamicContentEncabezado').innerHTML = `
            <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
        `;

            document.getElementById('textDinamicCalendar').innerHTML = `
            <span>Elige los 5 dias habiles a los que tienes derecho</span>
        `;


            // Cambia el contenido dinámico
            document.getElementById('dynamicContentFormaPago').innerHTML = `
            <div class="mb-2">
                <span>Forma de pago (Asignación automatica)</span>

                <input type="text" class="form-control mt-1" value="Permiso Especial" id="forma_pago" name="forma_pago" disabled>
            </div>
        `;
            // Abre el modal
            $('#modaTarjetas').modal('show');
        });

        document.getElementById('openModalIncapacidad').addEventListener('click', function() {
            // Cambia el título del modal
            document.getElementById('modalTitle').innerText = 'Incapacidad';

            document.getElementById('dynamicContentEncabezado').innerHTML = `
            <span>Selecciona los días naturales que no te presentarás</span>
        `;


            document.getElementById('dynamicContentEncabezado').innerHTML = `
            <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
            <span style="color: red"> Nota: Si aún no tienes el justificante, puedes
                anexarlo al recibirlo. Es obligatorio presentarlo; de lo contrario, se descontarán los dias
                de tus vacaciones.</span>        `;

            // Cambia el contenido dinámico
            document.getElementById('dynamicContentFormaPago').innerHTML = `
            <div class="mb-2">
                <span>Forma de pago (Asignación automatica)</span>
                <input type="text" class="form-control mt-1" value="Permiso Especial" id="forma_pago" name="forma_pago" disabled>
            </div>
        `;
            // Abre el modal
            $('#modaTarjetas').modal('show');
        });

        document.getElementById('openModalPermisoEspecial').addEventListener('click', function() {
            // Cambia el título del modal
            document.getElementById('modalTitle').innerText = 'Especiales';
            document.getElementById('textDinamicCalendar').innerHTML = `
            <span>Elige los 3 días naturales a los que tienes derecho</span>
        `;

            document.getElementById('dynamicContentEncabezado').innerHTML = `
            <div class="d-flex">
                            <div class="mr-4 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Permiso"
                                        id="fallecimiento" value="Fallecimiento de un familiar">
                                    <label class="form-check-label" for="fallecimiento">
                                        Fallecimiento de un familiar
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Permiso"
                                        id="matrimonio" value="Matrimonio del colaborador">
                                    <label class="form-check-label" for="matrimonio">
                                        Matrimonio del colaborador
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Permiso"
                                        id="academico" value="Motivos academicos/escolares">
                                    <label class="form-check-label" for="academico">
                                        Motivos academicos/escolares
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Permiso"
                                        id="asunto" value="Asuntos personales">
                                    <label class="form-check-label" for="asunto">Asuntos personales
                                    </label>
                                </div>
                            </div>

                            <div style="width: 50%">
                                <div id="persona_afectada" class="d-none">
                                    <span>Persona afectada: </span>
                                    <div>
                                        <select class="form-select mt-1" aria-label="Default select example"
                                            id='familiar' name="familiar">
                                            <option value="" selected>Seleccione</option>
                                            <option value="conyuge">Conyuge</option>
                                            <option value="hijos">Hijos</option>
                                            <option value="padres">Padres</option>
                                            <option value="hermanos">Hermanos</option>
                                            <option value="otros">Otros</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <div id='academicos' class="d-none ">
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <span>Tu posición es: </span>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="Posicion" id="hijo" value="hijo">
                                                    <label class="form-check-label" for="hijo">
                                                        Hijo
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="cs-form ">
                                                    <span>Hora de salida: </span>
                                                    <input placeholder="Hora de salida" type="time" class="form-control" name="hora_salida" />
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row ">
                                            <div class="col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="Posicion" id="colaborador" value="colaborador">
                                                    <label class="form-check-label" for="colaborador">
                                                        Colaborador
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <div class="cs-form mb-3">
                                                    <span>Hora de regreso: </span>
                                                    <input placeholder="Hora de regreso" type="time" class="form-control" name="hora_regreso" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div>
                            <textarea placeholder="Motivo" class="form-control" id="details" name="details" rows="3" required></textarea>
                        </div>     `;

            // Cambia el contenido dinámico
            document.getElementById('dynamicContentFormaPago').innerHTML = `
            <div class="mb-2">
                <span>Forma de pago (Asignación automatica)</span>
                <input type="text"  class="form-control mt-1"
                    value="Permiso Especial" id="forma_pago" name="forma_pago" disabled>

            </div>
        `;
            // Abre el modal
            $('#modaTarjetas').modal('show');


            $(document).ready(function() {
                $('input[name="Permiso"]').on('change', function() {
                    especiales($(this).attr('id'));
                });
            });

            const persona_afectada = document.querySelector('#persona_afectada');
            const academicos = document.querySelector('#academicos');

            function especiales(value) {
                console.log('value', value);
                switch (value) {
                    case 'fallecimiento':
                        persona_afectada.classList.remove('d-none');
                        academicos.classList.add('d-none');
                        break;
                    case 'academico':
                        persona_afectada.classList.add('d-none');
                        academicos.classList.remove('d-none');
                        break;
                    default:
                        persona_afectada.classList.add('d-none');
                        academicos.classList.add('d-none');
                }
            }
        });

        document.getElementById('miFormulario').addEventListener('submit', function(event) {
            // Evita el envío del formulario hasta validar todo
            event.preventDefault();
            console.log('Formulario enviado');

            var selectEncargado = document.getElementById('reveal_id');
            if (!selectEncargado || selectEncargado.value === "0") {
                alert('Selecciona un reveal_id');
                return; // Detiene el proceso si no se ha seleccionado un reveal_id
            }
            // Elimina los campos ocultos previamente creados (si existen)
            document.querySelectorAll('.hidden-input').forEach(input => input.remove());
            // Crear un objeto FormData para capturar los datos del formulario
            const formData = new FormData(this);
            // Crear un campo oculto para los días seleccionados (array 'selectedDays')
            const hiddenDates = document.createElement('input');
            hiddenDates.type = 'hidden';
            hiddenDates.name = 'dates';
            hiddenDates.value = //Mandar selectedDays como array
                JSON.stringify(selectedDays);
            hiddenDates.classList.add('hidden-input');
            this.appendChild(hiddenDates);

            // Si todo está bien, envía el formulario automáticamente
            this.submit();
        });
    </script>

    <style>
        .bg-success {
            background-color: #81C10C !important;
        }

        .bg-warning {
            background-color: #FFC107 !important;
        }


        .fc-day-disabled {
            background-color: #e6e6e6 !important;
            pointer-events: none;
        }

        .fc-toolbar-chunk>div {
            width: 380px;
            display: flex;
            justify-content: space-between
        }

        .fc-prev-button.fc-button.fc-button-primary {
            background-color: white;
            color: black;
            border-radius: 5px;
            border: 0px solid white;
        }

        .fc-prev-button.fc-button.fc-button-primary:focus {
            background-color: white;
            color: black;
            border-radius: 5px;
            border: 0px solid white;
            box-shadow: 0 0px 0px white;
        }


        /*fc-next-button fc-button fc-button-primary*/
        .fc-next-button.fc-button.fc-button-primary {
            background-color: white;
            color: black;
            border-radius: 5px;
            border: 0px solid white;
        }

        .fc-next-button.fc-button.fc-button-primary:focus {
            background-color: white;
            color: black;
            border-radius: 5px;
            border: 0px solid white;
            box-shadow: 0 0px 0px white;
        }


        .fc-theme-standard .fc-scrollgrid {
            border: 0px solid white !important;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            /* Espaciado interno */
            border: 0px solid white;
            /* Borde gris claro */
            text-align: center;
            /* Centrar el texto */
        }

        .fc-col-header-cell-cushion {
            color: black;
            text-decoration: none;
        }

        .fc-daygrid-day-number {
            color: black;
            text-decoration: none;
        }

        .fc-scrollgrid-sync-table {
            height: 280px !important;
        }

        .fc,
        .fc *,
        .fc :after,
        .fc :before {
            box-sizing: border-box;
            justify-content: center;
        }


        .fc-theme-standard .fc-daygrid-day-number {
            color: black;
            /* Color del número del día seleccionado */
            background-color: transparent;
            /* Fondo transparente para el número del día */
            font-weight: bold;
            /* Hacer el número más prominente */
        }

        .fc-theme-standard .fc-daygrid-day.fc-daygrid-day-selected {
            background-color: transparent;
            /* Fondo transparente para la celda seleccionada */
            border: none;
            /* Opcional: eliminar el borde si es necesario */
        }


        .fc-day-selected {
            background-color: #81C10C !important;
            border-radius: 17px;
            color: white !important;
            /* Color para los días intermedios */
        }

        .fc-day-start {
            background-color: rgb(59, 201, 23);
            /* Color para el primer día de la selección */
            color: white;
        }

        .fc-day-end {
            background-color: #d5c321;
            /* Color para el último día de la selección */
            color: white;
        }

        .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
            min-height: 0px;
        }

        .fc .fc-daygrid-day-top {
            margin-top: 2px !important;
        }

        .fc-theme-standard .fc-daygrid-day-number {
            width: 45% !important;
        }

        .modal-content {
            width: 1500px !important;
        }

        /*Estilo azul al seleccionar dia*/
        .fc .fc-highlight {
            background-color: transparent !important;
        }

        /*Estilo para quitar el color del dia actual */

        .fc .fc-daygrid-day.fc-day-today {
            background-color: transparent !important;
        }

        /*Estilo para quitar dias anteriores al que estamos*/
        .fc .fc-cell-shaded,
        .fc .fc-day-disabled {
            background-color: transparent !important;
        }


        /*Estilo para cambiar el tamaño*/
        .fc .fc-toolbar-title {
            font-size: 17px !important;
        }

        .fc-toolbar-title {
            margin-top: 8px !important;
        }

        /*Estilo para quitar el mnargin botton del titulo*/
        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 4px !important;
        }

        /*Estilo para darle margin bottom de los dias del calendario*/
        .fc .fc-view-harness {
            margin-bottom: 20px !important;
        }

        #calendario {
            width: 100%;
        }


        .highlighted-day-vacaciones {
            background-color: #81C10C !important;
            color: white !important;
            border-radius: 50%;
            padding: 0.5em;
        }

        .highlighted-day-permisos {
            background-color: #d5c321 !important;
            color: white !important;
            border-radius: 50%;
            padding: 0.5em;
        }


        /*Estilo para el div de los dias en Calendario General*/
        .custom-day-top-class {
            padding: 0px 25px !important;
        }
    </style>
@endsection
