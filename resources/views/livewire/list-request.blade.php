<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"># </th>
                <th scope="col">Solicitante</th>
                <th scope="col">Tipo</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha de Creacion</th>
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
                        {{ $request->direct_manager_status }}
                    </td>
                    <td>
                        {{ $request->created_at->diffForHumans() }}
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
                                        <p class="m-0">
                                            <b>Tipo de Pago: </b>
                                            {{ $request->payment }}
                                        </p>
                                        <p class="m-0">
                                            <b>Estado: </b>
                                            {{ $request->direct_manager_status }}
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
                                            <b>Fecha de Creacion:</b> {{ $request->created_at->diffForHumans() }}
                                        </p>
                                        <br>
                                        @if ($request->direct_manager_status == 'Pendiente')
                                            <button class="btn btn-info" wire:click="auth({{ $request }})"
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
    <script>
        document.addEventListener('livewire:load', function() {
            function aceptar(id) {

            }
        })
        window.addEventListener('swal', event => {
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
                    let respuesta = @this.autorizar(event.detail.id)
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire('¡Se ha autorizado la solicitud!', '', 'success')
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al autorizar!', '', 'error')
                        });
                } else if (result.isDenied) {
                    let respuesta = @this.rechazar(event.detail.id)
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire('La solicitud se ha rechazado correctamente', '', 'success')
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al rechazar!', '', 'error')
                        });
                }
            })
        })
    </script>
</div>
