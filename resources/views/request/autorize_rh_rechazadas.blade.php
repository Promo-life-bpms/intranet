@extends('layouts.app')

@section('content')
    {{-- Alertas --}}
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
        {{-- Spinner de carga --}}
        <div id="loadingSpinner"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(255, 255, 255, 0.8); z-index:9999; justify-content:center; align-items:center;">
            <div class="spinner-border text-primary" role="status">
            </div>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <h3 id="titlePage">Solicitudes autorizadas</h3>
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


                {{-- Tabla para canceladas por el usuario --}}
                <div id="tableCanceladas">
                    {{-- <div id="buttonUpdateDays" class="d-flex justify-content-end mb-2">
                        <button id="buttonReposicion" class="btn"
                            style="background-color: var(--color-target-1); color: white; ">Reposición</button>
                    </div> --}}
                    <table class="table" id="tableCanceladas" style="min-width: 100% !important;">
                        <thead style="background-color: #072A3B; color: white;">
                            <tr>
                                <th scope="col" style="text-align: center;">#</th>
                                <th scope="col" style="text-align: center;">Solicitante</th>
                                <th scope="col" style="text-align: center;">Tipo de solicitud</th>
                                <th scope="col" style="text-align: center;">Días ausente</th>
                                <th scope="col" style="text-align: center;">Justificante</th>
                                <th scope="col" style="text-align: center;">Motivo</th>
                                <th scope="col" style="text-align: center;">Estado</th>
                                {{-- <th scope="col" style="text-align: center;">Acciones</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($rechazadas) == 0)
                                <tr>
                                    <td colspan="7" style="text-align: center;">No tienes solicitudes</td>
                                </tr>
                            @endif
                            @foreach ($rechazadas as $rechazada)
                                <tr class="solicitud-row3" data-days3="{{ implode(',', $rechazada->days_absent) }}">
                                    <th style="text-align: center; align-content: center;" scope="row">
                                        {{ $rechazada->id }}</th>
                                    <td style="text-align: center;">{{ $rechazada->name }}</td>
                                    <td style="text-align: center;">{{ $rechazada->request_type }}</td>
                                    <td style="text-align: center;">
                                        @foreach ($rechazada->days_absent as $day)
                                            <div>
                                                {{ $day }}
                                            </div>
                                        @endforeach
                                    </td>


                                    <td style="text-align: center;">
                                        @if ($rechazada->file == null || $rechazada->file == '' || $rechazada->file == 'null' || $rechazada->file == 'undefined')
                                            <span>Sin archivo</span>
                                        @else
                                            <button type="button" id="file-link-{{ $rechazada->file }}"
                                                class="btn btn-link"
                                                onclick="viewFileDeny({{ json_encode($rechazada->file) }})">Ver
                                                archivo</button>
                                        @endif

                                    </td>
                                    <td style="text-align: center;">
                                        {{ $rechazada->commentary }}
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge bg-danger text-white">
                                            {{ $rechazada->rh_status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if (count($rechazadas) > 0)
                        <div class="d-flex justify-content-end">
                            {{ $rechazadas->appends(request()->input())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@stop


@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/modul_rh.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/all.min.css') }}">

@stop

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        function viewFileDeny(file) {
            console.log('file', file);
            const correctedFile = file.replace(/\\/g, '/');
            const baseUrl = window.location.origin;
            const fileUrl = baseUrl + '/' + correctedFile;
            window.open(fileUrl, '_blank');
        }

        //Poner por defecto la tarjeta 1 activa
        document.getElementById('tarjeta3').classList.add('tarjetaRh3-activa');

        document.getElementById('tarjeta1').addEventListener('click', function() {
            document.getElementById('loadingSpinner').style.display = 'flex';
            window.location.href = '/request/authorize-rh/aprobadas';
        });

        document.getElementById('tarjeta2').addEventListener('click', function() {
            document.getElementById('loadingSpinner').style.display = 'flex';
            window.location.href = '/request/authorize-rh/pendientes';
        });

        let tiempoEspera;
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
    </script>
@stop
