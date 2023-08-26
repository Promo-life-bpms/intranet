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
            @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ session('message') }}
                </div>
            @endif
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
                                @php
                                    $days = [];
                                @endphp
                                @if ($request->requestdays)
                                    @foreach ($request->requestdays as $day)
                                        @php
                                            $dayFormater = \Carbon\Carbon::parse($day->start);
                                            array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                        @endphp
                                    @endforeach
                                @else
                                    @foreach ($request->requestrejected as $day)
                                        @php
                                            $dayFormater = \Carbon\Carbon::parse($day->start);
                                            array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                        @endphp
                                    @endforeach
                                @endif
                                {!! implode('<br>', $days) !!}
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
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDetails{{ $request->id }}Label">
                                                    {{ $request->type_request }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Dias Ausente</th>
                                                                    <td>
                                                                        @php
                                                                            $days = [];
                                                                        @endphp
                                                                        @if ($request->requestdays)
                                                                            @foreach ($request->requestdays as $day)
                                                                                @php
                                                                                    $dayFormater = \Carbon\Carbon::parse($day->start);
                                                                                    array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                                                                @endphp
                                                                            @endforeach
                                                                        @else
                                                                            @foreach ($request->requestrejected as $day)
                                                                                @php
                                                                                    $dayFormater = \Carbon\Carbon::parse($day->start);
                                                                                    array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                                                                @endphp
                                                                            @endforeach
                                                                        @endif
                                                                        {!! implode('<br>', $days) !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Tiempo</th>
                                                                    <td>
                                                                        @if ($request->type_request == 'Salir durante la jornada')
                                                                            {{-- Start, es a que hora sale, end, a que hora regresas o termina su salida --}}
                                                                            @if ($request->end)
                                                                                {{ 'Entrada o Reingreso: ' . $request->end }}
                                                                            @endif
                                                                            @if ($request->start)
                                                                                {{ 'Salida: ' . $request->start . ' ' }}
                                                                            @endif
                                                                        @else
                                                                            Tiempo completo
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Tipo de Pago</th>
                                                                    <td>{{ $request->payment }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Motivo</th>
                                                                    <td>{{ $request->reason }}</td>
                                                                </tr>
                                                                @if ($request->type_request == 'Fallecimiento de familiar directo')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Familiar Directo
                                                                        </th>
                                                                        <td>{{ $request->opcion }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if ($request->type_request == 'Motivos academicas/escolares')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">El motivo escolar
                                                                            es de</th>
                                                                        <td>{{ $request->opcion }}</td>
                                                                    </tr>
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Estado Jefe Directo
                                                                    </th>
                                                                    <td>{{ $request->direct_manager_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Estado RH</th>
                                                                    <td>{{ $request->human_resources_status }}</td>
                                                                </tr>
                                                                @if ($request->reveal)
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Apoyo durante tu
                                                                            ausencia</th>
                                                                        <td>{{ $request->reveal->name . ' ' . $request->reveal->lastname }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if ($request->doc_permiso)
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Justificante</th>
                                                                        <td>
                                                                            @foreach (explode(',', $request->doc_permiso) as $archivo)
                                                                                <div class="alert alert-secondary p-1 m-0 mb-1 d-flex justify-content-between">
                                                                                    {{ Str::limit(substr($archivo, 11), 11, '...') }}
                                                                                    <a href="{{ asset('storage/archivosPermisos/' . $request->doc_permiso) }}"
                                                                                        target="_blank">
                                                                                        <div style="width: 20px;"
                                                                                            class="mx-1 aniversary-text text-primary">
                                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="currentColor"
                                                                                                class="w-6 h-6">
                                                                                                <path
                                                                                                    d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                                                                <path fill-rule="evenodd"
                                                                                                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"
                                                                                                    clip-rule="evenodd" />
                                                                                            </svg>
                                                                                        </div>
                                                                                    </a>
                                                                                </div>
                                                                            @endforeach

                                                                        </td>
                                                                    </tr>
                                                                @elseif($request->payment == 'Permiso especial')
                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <form
                                                                                action="{{ route('cargarArchivo', ['modelRequest' => $request->id]) }}"
                                                                                method="POST"
                                                                                enctype="multipart/form-data">
                                                                                @csrf
                                                                                <label for="archivo">Cargar
                                                                                    Justificante:</label>
                                                                                <input type="file"
                                                                                    name="archivos_permiso[]" id="archivo"
                                                                                    class="form-control form-control-sm"
                                                                                    accept=".pdf, .doc, .docx" multiple>
                                                                                <button type="submit"
                                                                                    class="btn btn-sm btn-primary">Cargar</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                @endif

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
