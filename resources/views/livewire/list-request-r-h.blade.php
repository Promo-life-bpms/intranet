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
                        <th scope="col">Fecha de Actualizacion</th>
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
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDetails{{ $request->id }}Label">
                                                    {{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <p class="m-0">
                                                    <b>Tipo de Solicitud: </b>
                                                    {{ $request->type_request }}

                                                </p>
                                                <br>
                                                <p class="m-0"><b>Vacaciones Disponibles</b></p>
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
                                                <p class="m-0">Dias de vacaciones diponibles: <b
                                                        id="diasDisponiblesEl">
                                                        {{ $vacations }} </b> </p>
                                                @if ($request->employee->user->employee->take_expired_vacation)
                                                    <p class="m-0"><b>{{ $vacationsExpired }} </b> dias disponibles
                                                        que estan por expirar. </p>
                                                @endif
                                                @foreach ($dataVacations as $item)
                                                    @if ($item->dv >= 0)
                                                        @php
                                                            $dayFormater = \Carbon\Carbon::parse($item->cutoff_date);
                                                        @endphp
                                                        <p class="m-0"><b>{{ $item->dv }} </b> dias disponibles
                                                            hasta el
                                                            <b>
                                                                {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}</b>
                                                        </p>
                                                    @endif
                                                @endforeach
                                                <br>
                                                <p class="m-0">
                                                    <b>Tipo de Pago: </b>
                                                    {{ $request->payment }}
                                                </p>
                                                <p class="m-0">
                                                    <b>Estado: </b>
                                                    {{ $request->human_resources_status }}
                                                </p>
                                                <br>
                                                <p class="m-0"> <b> Dias ausente:</b>
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
                                                    @if ($request->type_request == 'Salir durante la jornada')
                                                        @if ($request->start != null)
                                                            {{ 'Salida: ' . $request->start }}
                                                        @endif
                                                        @if ($request->end != null)
                                                            {{ 'Entrada o Reingreso: ' . $request->end . ' ' }}
                                                        @endif
                                                    @else
                                                        Tiempo completo
                                                    @endif
                                                </p>
                                                <br>
                                                <p class="m-0">
                                                    <b>Motivo:</b> {{ $request->reason }}
                                                </p>
                                                <p class="m-0">
                                                    <b>Fecha de Creacion:</b>
                                                    {{ $request->created_at->diffForHumans() }}
                                                </p>
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
                        <th scope="col">Aprobado por</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Fecha de Actualizacion</th>
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
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modalDetails{{ $request->id }}Label">
                                                    {{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-left">
                                                <p class="m-0">
                                                    <b>Tipo de Solicitud: </b>
                                                    {{ $request->type_request }}
                                                </p>
                                                <br>
                                                <p class="m-0"><b>Vacaciones Disponibles</b></p>
                                                @php
                                                    $dataVacations = $request->employee->user->vacationsAvailables;
                                                    $vacations = $dataVacations->sum('dv');
                                                @endphp
                                                <p class="m-0">Dias de vacaciones diponibles: <b
                                                        id="diasDisponiblesEl">
                                                        {{ $vacations }} </b> </p>
                                                @foreach ($dataVacations as $item)
                                                    @php
                                                        $dayFormater = \Carbon\Carbon::parse($item->cutoff_date);
                                                    @endphp
                                                    <p class="m-0"><b>{{ $item->dv }} </b> dias disponibles
                                                        hasta el
                                                        <b>
                                                            {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}</b>
                                                    </p>
                                                @endforeach
                                                <br>
                                                <p class="m-0">
                                                    <b>Tipo de Pago: </b>
                                                    {{ $request->payment }}
                                                </p>
                                                @if ($request->opcion)
                                                    <p class="m-0">
                                                        @if ($request->opcion == null)
                                                        @else
                                                            <b>Opcion: </b>
                                                            {{ $request->opcion }}
                                                        @endif

                                                    </p>
                                                @endif

                                                <p class="m-0">
                                                    <b>Estado: </b>
                                                    {{ $request->human_resources_status }}
                                                </p>
                                                <p class="m-0">
                                                    @if ($request->doc_permiso == null)
                                                        {{ 'No hay archivo' }}
                                                    @else
                                                        <a>Archivo</a>

                                                        <a href="{{ asset('storage/archivos/' . basename($request->doc_permiso)) }}"
                                                            target="_blank">{{ basename($request->doc_permiso) }}</a>
                                                    @endif

                                                </p>
                                                <br>
                                                <p class="m-0"> <b> Dias ausente:</b>
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
                                                    @if ($request->type_request == 'Salir durante la jornada')
                                                        @if ($request->start != null)
                                                            {{ 'Salida: ' . $request->start }}
                                                        @endif
                                                        @if ($request->end != null)
                                                            {{ 'Entrada o Reingreso: ' . $request->end . ' ' }}
                                                        @endif
                                                    @else
                                                        Tiempo completo
                                                    @endif
                                                </p>
                                                <br>
                                                <p class="m-0">
                                                    <b>Motivo:</b> {{ $request->reason }}
                                                </p>
                                                <p class="m-0">
                                                    <b>Fecha de Creacion:</b>
                                                    {{ $request->created_at->diffForHumans() }}
                                                </p>
                                                <br>
                                                @if ($request->human_resources_status == 'Pendiente' && $request->direct_manager_status == 'Aprobada')
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
