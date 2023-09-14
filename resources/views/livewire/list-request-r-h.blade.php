<div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home"
                aria-selected="true">Autorizadas</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                aria-controls="profile" aria-selected="false">Sin autorizar</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="">Buscar Usuario</label>
                        <input type="text" class="form-control" wire:model="searchName">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Buscar por estatus</label>
                        <select name="" id="" class="form-control" wire:model="searchStatus">
                            <option value="">Todos</option>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Aprobada">Aprobada</option>
                            <option value="Rechazada">Rechazada</option>
                        </select>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"># </th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Aprobado por</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de Actualización</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr wire:key="item-{{ $request->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                            <td>{{ $request->type_request }}</td>
                            <td>
                                {{ $request->manager->user->name . ' ' . $request->manager->user->lastname }}
                            </td>
                            <td>
                                {{ $request->human_resources_status }}
                            </td>
                            <td>
                                {{ $request->updated_at->diffForHumans() }}
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
                                                    {{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <div class="row">
                                                    <div class="w-100">
                                                        @php
                                                            $dataVacations = $request->employee->user->vacationsAvailables;
                                                            $vacations = $request->employee->user->employee->take_expired_vacation ? $request->employee->user->vacationsComplete()->sum('dv') : $request->employee->user->vacationsAvailables()->sum('dv');
                                                            $vacationsExpired =
                                                                $request->employee->user->vacationsComplete()->sum('dv') -
                                                                ($request->employee->user
                                                                    ->vacationsComplete()
                                                                    ->where('period', '<', 3)
                                                                    ->sum('dv') > 0
                                                                    ? $request->employee->user
                                                                        ->vacationsComplete()
                                                                        ->where('period', '<', 3)
                                                                        ->sum('dv')
                                                                    : 0);
                                                        @endphp
                                                        <p class="m-0">Días de vacaciones diponibles: <b
                                                                id="diasDisponiblesEl">
                                                                {{ $vacations }} </b> </p>
                                                        @if ($request->employee->user->employee->take_expired_vacation)
                                                            <p class="m-0"><b>{{ $vacationsExpired }} </b> días
                                                                disponibles
                                                                que estan por expirar. </p>
                                                        @endif
                                                        @foreach ($dataVacations as $item)
                                                            @if ($item->dv >= 0)
                                                                @php
                                                                    $dayFormater = \Carbon\Carbon::parse($item->cutoff_date);
                                                                @endphp
                                                                <p class="m-0"><b>{{ $item->dv }} </b> días
                                                                    disponibles
                                                                    hasta el
                                                                    <b>
                                                                        {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}</b>
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                        <br>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Tipo de solicitud
                                                                    </th>
                                                                    <td>{{ $request->type_request }}
                                                                    </td>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Días ausente</th>
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
                                                                    <th style="vertical-align: middle">Tipo de pago</th>
                                                                    <td>{{ $request->payment }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Motivo</th>
                                                                    <td>{{ $request->reason }}</td>
                                                                </tr>
                                                                @if ($request->type_request == 'Fallecimiento de familiar directo')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Familiar
                                                                            Directo
                                                                        </th>
                                                                        <td>{{ $request->opcion }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if ($request->type_request == 'Motivos academicas/escolares')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">El motivo
                                                                            escolar
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
                                                                    <th style="vertical-align: middle">Estado jefe
                                                                        directo
                                                                    </th>
                                                                    <td>{{ $request->direct_manager_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Estado RH</th>
                                                                    <td>{{ $request->human_resources_status }}</td>
                                                                </tr>
                                                                @if ($request->reveal)
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Apoyo durante
                                                                            tu
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
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="currentColor"
                                                                                                class="w-6 h-6">
                                                                                                <path
                                                                                                    d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                                                                <path
                                                                                                    fill-rule="evenodd"
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
                                                                <tr>
                                                                    <th style="vertical-align: middle">Fecha de
                                                                        creación</th>
                                                                    <td>{{ $request->created_at->format('d/M/Y') }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <br>
                                                @if ($request->human_resources_status == 'Pendiente')
                                                    <button class="btn btn-info" onclick="auth({{ $request->id }})"
                                                        data-bs-dismiss="modal">Aceptar - Rechazar
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="text-center">
                {{ $requests->links() }}
            </div>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <p>Solicitudes que no se han aprobado por los jefes directos</p>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"># </th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Jefe directo</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de actualización</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requestsWithoutAuth as $request)
                        <tr wire:key="item-{{ $request->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                            <td>{{ $request->type_request }}</td>
                            <td>
                                {{ $request->manager->user->name . ' ' . $request->manager->user->lastname }}
                            </td>
                            <td>
                                {{ $request->human_resources_status }}
                            </td>
                            <td>
                                {{ $request->updated_at->diffForHumans() }}
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
                                                    {{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <div class="row">
                                                    <div class="w-100">
                                                        @php
                                                            $dataVacations = $request->employee->user->vacationsAvailables;
                                                            $vacations = $request->employee->user->employee->take_expired_vacation ? $request->employee->user->vacationsComplete()->sum('dv') : $request->employee->user->vacationsAvailables()->sum('dv');
                                                            $vacationsExpired =
                                                                $request->employee->user->vacationsComplete()->sum('dv') -
                                                                ($request->employee->user
                                                                    ->vacationsComplete()
                                                                    ->where('period', '<', 3)
                                                                    ->sum('dv') > 0
                                                                    ? $request->employee->user
                                                                        ->vacationsComplete()
                                                                        ->where('period', '<', 3)
                                                                        ->sum('dv')
                                                                    : 0);
                                                        @endphp
                                                        <p class="m-0">Días de vacaciones diponibles: <b
                                                                id="diasDisponiblesEl">
                                                                {{ $vacations }} </b> </p>
                                                        @if ($request->employee->user->employee->take_expired_vacation)
                                                            <p class="m-0"><b>{{ $vacationsExpired }} </b> días
                                                                disponibles
                                                                que estan por expirar. </p>
                                                        @endif
                                                        @foreach ($dataVacations as $item)
                                                            @if ($item->dv >= 0)
                                                                @php
                                                                    $dayFormater = \Carbon\Carbon::parse($item->cutoff_date);
                                                                @endphp
                                                                <p class="m-0"><b>{{ $item->dv }} </b> días
                                                                    disponibles
                                                                    hasta el
                                                                    <b>
                                                                        {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}</b>
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                        <br>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table">
                                                            <tbody>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Tipo de
                                                                        solicitud
                                                                    </th>
                                                                    <td>{{ $request->type_request }}
                                                                    </td>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Días ausente
                                                                    </th>
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
                                                                    <th style="vertical-align: middle">Tipo de pago
                                                                    </th>
                                                                    <td>{{ $request->payment }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Motivo</th>
                                                                    <td>{{ $request->reason }}</td>
                                                                </tr>
                                                                @if ($request->type_request == 'Fallecimiento de familiar directo')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Familiar
                                                                            Directo
                                                                        </th>
                                                                        <td>{{ $request->opcion }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if ($request->type_request == 'Motivos academicas/escolares')
                                                                    <tr>
                                                                        <th style="vertical-align: middle">El motivo
                                                                            escolar
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
                                                                    <th style="vertical-align: middle">Estado jefe
                                                                        directo
                                                                    </th>
                                                                    <td>{{ $request->direct_manager_status }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th style="vertical-align: middle">Estado RH</th>
                                                                    <td>{{ $request->human_resources_status }}</td>
                                                                </tr>
                                                                @if ($request->reveal)
                                                                    <tr>
                                                                        <th style="vertical-align: middle">Apoyo
                                                                            durante
                                                                            tu
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
                                                                                                viewBox="0 0 24 24"
                                                                                                fill="currentColor"
                                                                                                class="w-6 h-6">
                                                                                                <path
                                                                                                    d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                                                                                <path
                                                                                                    fill-rule="evenodd"
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
                                                                <tr>
                                                                    <th style="vertical-align: middle">Fecha de
                                                                        creación</th>
                                                                    <td>{{ $request->created_at->format('d/M/Y') }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function auth(id) {
            Swal.fire({
                title: '¿Deseas responder a esta solicitud?',
                showDenyButton: true,
                showCancelButton: true,
                cancelButtonText: 'Salir',
                confirmButtonText: 'Aceptar',
                denyButtonText: `Rechazar`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    let respuesta = @this.autorizar(id)
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire('¡Se ha autorizado la solicitud!', '', 'success')
                            }
                            if (response == 2) {
                                Swal.fire(
                                    'No tiene suficientes dias disponibles para aprobar esta solicitud',
                                    '', 'success')
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al autorizar!', '', 'error')
                        });
                } else if (result.isDenied) {
                    let respuesta = @this.rechazar(id)
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire('La solicitud de ha rechazado correctamente', '', 'success')
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al rechazar!', '', 'error')
                        });
                }
            })
        }
    </script>
</div>
