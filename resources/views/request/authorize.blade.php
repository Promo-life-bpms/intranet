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
                        <input type="search" class="form-control" placeholder="Buscar por nombre">
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
                            <strong> Total 150 </strong>
                        </div>

                        <div class="d-flex justify-content-center">
                            <span>Colaboradores</span>
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
                        <tr>
                            <th style="text-align: center;" scope="row">1</th>
                            <td style="text-align: center;">Pedro Zaragoza Bonilla</td>
                            <td style="text-align: center;">Solicitar vacaciones</td>
                            <td style="text-align: center;">9,10,11,15 de ago de 2024</td>
                            <td style="text-align: center;">Federico Solano Reyes</td>
                            <td style="text-align: center;">Ana Miriam Pérez Maya</td>
                            <td style="text-align: center;">
                                <button class="btn btn-link openModalDetails" id="openModalDetails">Ver y autorizar</button>
                        </tr>
                    </tbody>
                </table>

            </div>

            {{-- Tabla para pendientes --}}
            <div id="tablePendientes" style="display: none">
                <table class="table" style="min-width: 100% !important;">
                    <thead style="background-color: #072A3B; color: white;">
                        <tr>
                            <th scope="col" style="text-align: center;">#</th>
                            <th scope="col" style="text-align: center;">Solicitante</th>
                            <th scope="col" style="text-align: center;">Tipo de Solicitud</th>
                            <th scope="col" style="text-align: center;">Dias ausente</th>
                            <th scope="col" style="text-align: center;">Aprobado por (Jefe)</th>
                            <th scope="col" style="text-align: center;">Autorizar solicitud</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th style="text-align: center;" scope="row">1</th>
                            <td style="text-align: center;">Pedro Zaragoza Bonilla</td>
                            <td style="text-align: center;">Solicitar vacaciones</td>
                            <td style="text-align: center;">9,10,11,15 de ago de 2024</td>
                            <td style="text-align: center;">Federico Solano Reyes</td>
                            <td style="text-align: center;">
                                <button class="btn btn-link openModalDetailsActualizar" id="openModalDetailsActualizar">Ver
                                    y
                                    Actualizar</button>
                        </tr>
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
                        <tr>
                            <th style="text-align: center;" scope="row">1</th>
                            <td style="text-align: center;">Pedro Zaragoza Bonilla</td>
                            <td style="text-align: center;">Solicitar vacaciones</td>
                            <td style="text-align: center;">9,10,11,15 de ago de 2024</td>
                            <td style="text-align: center;">Sin justificante</td>
                            <td style="text-align: center;">Motivo de mi cancelación</td>
                            <td style="text-align: center;">
                                <a href="#" class="btn btn-link">
                                    <input type="number" style="max-width: 30%; text-align: center;">
                                </a>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


        <div class="modal fade bd-example-modal-lg" id="modalDetails" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Detalles de la Solicitud</h5>
                        <button id="closemodalDetails" type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <form id="miFormulario"  --}}
                        {{-- action="create/vacation/or/leave/request"
                          method="POST" --}}>
                        {{-- @csrf --}}

                        <div class="d-flex justify-content-end">
                            <span>Fecha de creación:</span>
                            <span>23 de septiembre del 2024 </span>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="d-flex justify-content-center align-items-center mt-3">
                                    {{-- style="background-color: transparent; min-height: 105px; min-width: 110px; border-radius: 100px; box-shadow: 0 4px 8px rgba(0,0,0,0.2)"> --}}
                                    <img src="https://www.w3schools.com/howto/img_avatar.png" alt="Avatar"
                                        style="width: 70%; border-radius: 100px; box-shadow: 0 8px 8px rgba(0,0,0,0.2)">
                                </div>
                            </div>
                            <div class="col-9">
                                <div>
                                    <strong>Pedro Zaragoza Bonilla</strong>
                                </div>

                                <div class="mt-2">
                                    <span>Te queda
                                        <strong>
                                            1
                                        </strong>

                                        días disponible que vence el
                                        <strong>
                                            14 de marzo de 2025.
                                        </strong>
                                    </span>
                                </div>


                                <div class="mt-2">
                                    <span>Te queda
                                        <strong>
                                            3
                                        </strong>

                                        días disponible que vence el
                                        <strong>
                                            14 de marzo de 2025.
                                        </strong>
                                    </span>
                                </div>

                                <div class="d-flex mt-3">
                                    <div class="mr-3">
                                        <div>
                                            <span>JEFE DIRECTO</span>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <span style="padding: 8px 15px;"
                                                class="badge bg-warning text-dark">Pendiente</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <span>RECURSOS HUMANOS</span>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <span style="padding: 8px 15px;"
                                                class="badge bg-warning text-dark">Pendiente</span>
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
                                    <span>Solicita vacaciones</span>
                                </div>
                                <div class="mt-2">
                                    <span> - </span>
                                </div>
                                <div class="mt-2">
                                    <span>9, 10, 11, 12, 13, 14, 15, 16, 17 de septiembre de 2024</span>
                                </div>

                                <div class="mt-2">
                                    <span>A cuenta de vacaciones</span>
                                </div>

                                <div class="mt-2">
                                    <span>Tiempo completo </span>
                                </div>

                                <div class="mt-2">
                                    <span>Sofia Montes Ocampo </span>
                                </div>

                                <div class="mt-2">
                                    <span>Sin justificante</span>
                                </div>
                            </div>
                        </div>

                        <div id="buttonModifi" style="display: none">
                            <button id="denyRequest" class="btn btn-danger mr-2">Rechazar</button>
                            <button class="btn btn-primary">Aprobar</button>
                        </div>

                        {{-- </form> --}}
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
                        {{-- <form id="miFormulario" --}}
                        {{-- action="create/vacation/or/leave/request"
                          method="POST" --}}>
                        @csrf

                        <textarea class="form-control" placeholder="Motivo de rechazo" rows="3"></textarea>


                        <div class="d-flex justify-content-end mt-2">
                            <button class="btn btn-primary">Enviar</button>
                        </div>

                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

@section('scripts')
    <script>
        // Evento cuando se hace clic en el botón para abrir el modal
        document.getElementById('openModalDetails').addEventListener('click', function() {
            document.getElementById('buttonModifi').style.display = 'flex';
            document.getElementById('buttonModifi').style.justifyContent = 'flex-end';
            $('#modalDetails').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
        });


        // Evento cuando se hace clic en el botón para cerrar el modal
        document.getElementById('closemodalDetails').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            document.getElementById('buttonModifi').style.display = 'none';

        });

        document.getElementById('denyRequest').addEventListener('click', function() {
            $('#modalDetails').modal('hide');
            $('#modalDeny').modal({
                backdrop: 'static', // Evita que el modal se cierre al hacer clic fuera
                keyboard: false // Desactiva el cierre con la tecla "Esc"
            }).modal('show');
        });

        document.getElementById('closeModalDeny').addEventListener('click', function() {
            $('#modalDeny').modal('hide');
        });
    </script>
@stop
