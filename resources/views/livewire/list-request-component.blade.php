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
                @foreach ($myrequests as $solicitud)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $solicitud->type_request }}</td>
                        <td>
                            @php
                                $days = [];
                            @endphp
                            @if ($solicitud->requestdays)
                                @foreach ($solicitud->requestdays as $day)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($day->start);
                                        array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                    @endphp
                                @endforeach
                            @else
                                @foreach ($solicitud->requestrejected as $day)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($day->start);
                                        array_push($days, $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y'));
                                    @endphp
                                @endforeach
                            @endif
                            {!! implode('<br>', $days) !!}
                        </td>
                        <td>
                            @if ($solicitud->human_resources_status == 'Pendiente' && $solicitud->direct_manager_status == 'Pendiente')
                                <b> En espera de la autorización del jefe directo</b>
                            @elseif ($solicitud->human_resources_status == 'Pendiente' && $solicitud->direct_manager_status == 'Aprobada')
                                <b> En espera de la autorización de RH</b>
                            @elseif ($solicitud->human_resources_status == 'Aprobada' && $solicitud->direct_manager_status == 'Aprobada')
                                <b> Solicitud Aprobada</b>
                            @elseif ($solicitud->direct_manager_status == 'Rechazada')
                                <b> Rechazado por el jefe directo</b>
                            @elseif ($solicitud->human_resources_status == 'Rechazada')
                                <b> Rechazado por RH</b>
                            @endif
                        </td>
                        <td class="text-center">

                            <button type="button" class="btn btn-info btn-sm"
                                wire:click="showRequest({{ $solicitud->id }})">
                                <div style="width: 2rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </button>

                            {{-- <a class="btn btn-warning btn-sm" href="{{ route('request.edit', ['request'=>$solicitud->id]) }}">
                                <div style="width: 2rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </div>
                            </a> --}}

                            @if ($solicitud->human_resources_status == 'Pendiente' && $solicitud->direct_manager_status == 'Pendiente')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="eliminar({{ $solicitud->id }})">
                                    <div style="width: 2rem">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </div>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $myrequests->links() }}
        </div>
        <!-- Modal -->
        @if ($request)
            <div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1"
                aria-labelledby="modalDetailsLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDetailsLabel">
                                {{ $request->type_request }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-left" wire:loading.class="opacity-50" wire:target="showRequest">
                            <div class="row">
                                <div class="col-md-12">
                                    @if (session('updateFile'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('updateFile') }}
                                        </div>
                                    @endif
                                </div>
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
                                                        @if ($request->start)
                                                            {{ 'Salida: ' . $request->start . ' ' }}
                                                        @endif
                                                        <br>
                                                        @if ($request->end)
                                                            {{ 'Entrada o Reingreso: ' . $request->end }}
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
                                                    <th
                                                        style="vertical-align: middle; display: flex; flex-direction: column; ">
                                                        Justificante
                                                        <button class="btn-block btn btn-sm btn-warning mb-2"
                                                            wire:click="cambiarEditarJustificante">
                                                            Cambiar Justificante
                                                        </button>
                                                    </th>

                                                    <td>

                                                        @foreach (explode(',', $request->doc_permiso) as $archivo)
                                                            <div
                                                                class="alert alert-secondary p-1 m-0 mb-1 d-flex justify-content-between">
                                                                {{ Str::limit(substr($archivo, 11), 11, '...') }}
                                                                <a href="{{ asset('storage/archivosPermisos/' . $request->doc_permiso) }}"
                                                                    target="_blank">
                                                                    <div style="width: 20px;"
                                                                        class="mx-1 aniversary-text text-primary">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 24 24" fill="currentColor"
                                                                            class="w-6 h-6">
                                                                            <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
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
                                            @endif
                                            @if ($editarJustificante)
                                                <tr>
                                                    <td colspan="2">
                                                        <label for="archivo">Cargar
                                                            Justificante:</label>
                                                        <input type="file" name="archivos_permiso[]"
                                                            wire:model="archivos_permiso" id="archivo"
                                                            class="form-control form-control-sm"
                                                            accept=".pdf, .doc, .docx" multiple>
                                                        <button wire:click="subirJustificante({{ $request->id }})"
                                                            class="btn btn-sm btn-primary"
                                                            wire:loading.attr="disabled"
                                                            wire:target="archivos_permiso">Cargar</button>
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <style>
        .nav-link {
            font-size: 20px;
        }
    </style>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function eliminar(id) {
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
                    @this.deleteRequest(id)
                }
            })
        }
        window.addEventListener('showRequest', event => {
            $('#modalDetails').modal('show');
        })
    </script>
</div>
