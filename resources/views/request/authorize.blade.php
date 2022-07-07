@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Solicitudes recibidas para autorizacion</h3>
            </div>
        </div>
        <div class="card-body">
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
                        <tr>
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
                                                <p class="m-0">
                                                    <b>Tipo de Pago: </b>
                                                    {{ $request->payment }}
                                                </p>
                                                <br>
                                                <p class="m-0"> <b> Dias ausente:</b>
                                                    @foreach ($request->requestdays as $day)
                                                        @php
                                                            $dayFormater = \Carbon\Carbon::parse($day->start);
                                                        @endphp
                                                        {{ $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y') }}
                                                    @endforeach
                                                </p>
                                                <p class="m-0">
                                                    <b>Tiempo:</b>
                                                    @if ($request->payment == 'Salir durante la jornada')
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
                                                    <b>Motivo:</b> {{ $request->reason }}
                                                </p>
                                                <p class="m-0">
                                                    <b>Fecha de Creacion:</b> {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cerrar</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
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
