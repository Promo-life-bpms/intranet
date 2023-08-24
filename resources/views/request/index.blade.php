@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Mis solicitudes</h3>
                <a href="{{ route('request.create') }}" type="button" class="btn btn-success">Agregar</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"># </th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Fechas de ausencia</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($myrequests as $request)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->type_request }}</td>
                            <td>
                                @foreach ($request->requestdays as $day)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($day->start);
                                    @endphp
                                    {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                    <br>
                                @endforeach
                                @foreach ($request->requestrejected as $day)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($day->start);
                                    @endphp
                                    {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                    <br>
                                @endforeach
                            </td>
                            <td>
                                @if ($request->human_resources_status == 'Pendiente' && $request->direct_manager_status == 'Pendiente')
                                    <b> En espera de la autorización del jefe directo</b>
                                @elseif ($request->human_resources_status == 'Pendiente' && $request->direct_manager_status == 'Aprobada')
                                    <b> En espera de la autorización de RH</b>
                                @elseif ($request->human_resources_status == 'Aprobada' && $request->direct_manager_status == 'Aprobada')
                                    <b> Solicitud Aprobada</b>
                                @elseif ($request->direct_manager_status == 'Rechazada')
                                    <b> Rechazado por el jefe directo</b>
                                @elseif ($request->human_resources_status == 'Rechazada')
                                    <b> Rechazado por RH</b>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalDetails{{ $request->id }}">
                                    Ver Detalles
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="modalDetails{{ $request->id }}" tabindex="-1"
                                    aria-labelledby="modalDetails{{ $request->id }}Label" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDetails{{ $request->id }}Label">
                                                    {{ $request->type_request }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <p class="m-0"><b> Dias ausente:</b>
                                                    @foreach ($request->requestdays as $day)
                                                        @php
                                                            $dayFormater = \Carbon\Carbon::parse($day->start);
                                                        @endphp
                                                        {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                                    @endforeach
                                                    @foreach ($request->requestrejected as $day)
                                                        @php
                                                            $dayFormater = \Carbon\Carbon::parse($day->start);
                                                        @endphp
                                                        {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                                    @endforeach
                                                </p>
                                                <p class="m-0">
                                                    <b>Tiempo:</b>
                                                    @if ($request->payment != 'A cuenta de vacaciones')
                                                        @if ($request->start != null)
                                                            {{ 'Entrada: ' . $request->start }}
                                                        @endif
                                                        @if ($request->end != null)
                                                            {{ 'Salida: ' . $request->end . ' ' }}
                                                        @endif
                                                    @else
                                                        Tiempo completo
                                                    @endif
                                                </p>
                                                <p class="m-0">
                                                    <b>Tipo de Pago: </b>{{ $request->payment }}
                                                </p>
                                                <p class="m-0">
                                                    <b>Estado Jefe Directo:</b> {{ $request->direct_manager_status }}
                                                </p>
                                                <p class="m-0">
                                                    <b> Estado RH:</b> {{ $request->human_resources_status }}
                                                </p>
                                                <p class="m-0">
                                                    <b> Motivo: </b>{{ $request->reason }}
                                                </p>
                                                <p class="m-0">
                                                    @if ($request->opcion == null)
                                                    @else
                                                        <b> Opcion: </b>{{ $request->opcion }}
                                                    @endif

                                                </p>
                                                @if ($request->reveal)
                                                    <p class="m-0">
                                                        <b>Apoyo durante tu ausencia:</b>
                                                        {{ $request->reveal->name . ' ' . $request->reveal->lastname }}
                                                    </p>
                                                @endif
                                                @if ($request->doc_permiso == null)
                                                    <b>No hay archivo cargado</b>
                                                    <form action="{{ route('cargarArchivo') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $request->id }}">
                                                        <label for="archivo">Cargar archivo:</label>
                                                        <input type="file" name="archivo" id="archivo"
                                                            accept=".pdf, .doc, .docx">
                                                        <button type="submit">Cargar</button>
                                                    </form>
                                                @else
                                                    {{-- <a href="{{ $request->doc_permiso }}" target="_blank">
                                                            {{ $request->doc_permiso }}
                                                        </a> --}}
                                                    {{--    <button > --}}
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <b>Archivo</b>
                                                        {{ basename($request->doc_permiso) }}

                                                        <a href="{{ $request->doc_permiso }}" target="_blank">
                                                            {{-- {{ $request->doc_permiso }} --}}
                                                            <div style="width: 30px;" class="mx-1 aniversary-text">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                    fill="currentColor" class="w-6 h-6">
                                                                    <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                                    <path fill-rule="evenodd"
                                                                        d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                                                        clip-rule="evenodd" />
                                                                </svg>


                                                            </div>
                                                        </a>
                                                    </div>
                                                    {{--
                                                        </button> --}}
                                                @endif
                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($request->human_resources_status == 'Pendiente' && $request->direct_manager_status == 'Pendiente')
                                    <form class="form-delete"
                                        action="{{ route('request.destroy', ['request' => $request->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Borrar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>





@stop


@section('styles')
    <style>
        .nav-link {
            font-size: 20px;
        }
    </style>
@stop
@section('scripts')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('.form-delete').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡El registro se eliminará permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@stop
