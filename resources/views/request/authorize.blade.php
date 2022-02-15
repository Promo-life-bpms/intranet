@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Solicitudes recibidas para autorizacion</h3>
                <a href="{{ route('request.create') }}" type="button" class="btn btn-success">Agregar</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                        aria-controls="home" aria-selected="true">
                        @php
                            $contador = 0;
                            foreach (auth()->user()->unreadNotifications as $notification) {
                                if ($notification->data['direct_manager_status'] == 'Pendiente') {
                                    $contador = $contador + 1;
                                }
                            }
                        @endphp
                        Pendientes
                        <span class="badge bg-primary"><?= $contador ?> </span>
                        </span>

                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="false">Aprobadas</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab"
                        aria-controls="contact" aria-selected="false">Rechazadas</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h6>Solicitudes pendientes</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col"># </th>
                                            <th scope="col">Solicitante</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Pago</th>
                                            <th scope="col">Fechas de ausencia</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Jefe status </th>
                                            <th scope="col">RH status</th>
                                            <th style="width: 10%" scope="col">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($requests as $request)
                                            @if ($request->direct_manager_status == 'Pendiente')
                                                <tr>

                                                    <td>{{ $request->id }}</td>
                                                    <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                    </td>
                                                    <td>{{ $request->type_request }}</td>
                                                    <td>{{ $request->payment }}</td>
                                                    <td>
                                                        @foreach ($requestDays as $requestDay)
                                                            @if ($request->id == $requestDay->requests_id)
                                                                {{ $requestDay->start }} ,

                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $request->reason }}</td>
                                                    <td><b> {{ $request->direct_manager_status }} </b></td>
                                                    <td><b>{{ $request->human_resources_status }}</b> </td>
                                                    <td>
                                                        <a style="width:100%"
                                                            href="{{ route('request.authorize.edit', ['request' => $request->id]) }}"
                                                            type="button" class="btn btn-primary">Detalles</a>
                                                    </td>
                                                </tr>
                                            @else

                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h6>Solicitudes aprobadas</h6>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col"># </th>
                                            <th scope="col">Solicitante</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Pago</th>
                                            <th scope="col">Fechas de ausencia</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Jefe status </th>
                                            <th scope="col">RH status</th>
                                            @if (auth()->user()->unreadNotifications->count() > 0)
                                                <th style="width: 200px" scope="col">
                                                    Opciones
                                                </th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($requests as $request)
                                            @if ($request->human_resources_status == 'Aprobada' && $request->direct_manager_status == 'Aprobada')
                                                <tr>

                                                    <td>{{ $request->id }}</td>
                                                    <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                    </td>
                                                    <td>{{ $request->type_request }}</td>
                                                    <td>{{ $request->payment }}</td>
                                                    <td>
                                                        @foreach ($requestDays as $requestDay)
                                                            @if ($request->id == $requestDay->requests_id)
                                                                {{ $requestDay->start }} ,

                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $request->reason }}</td>
                                                    <td><b>{{ $request->direct_manager_status }}</b> </td>
                                                    <td><b>{{ $request->human_resources_status }}</b> </td>
                                                    <td>
                                                        <a style="width:100%"
                                                            href="{{ route('request.authorize.edit', ['request' => $request->id]) }}"
                                                            type="button" class="btn btn-primary">Detalles</a>
                                                    </td>
                                                </tr>
                                            @else

                                            @endif

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h6>Solicitudes rechazadas</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col"># </th>
                                            <th scope="col">Solicitante</th>
                                            <th scope="col">Tipo</th>
                                            <th scope="col">Pago</th>
                                            <th scope="col">Fechas de ausencia</th>
                                            <th scope="col">Motivo</th>
                                            <th scope="col">Jefe status </th>
                                            <th scope="col">RH status</th>
                                            @if (auth()->user()->unreadNotifications->count() > 0)
                                                <th style="width: 200px" scope="col">
                                                    Opciones
                                                </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($requests as $request)
                                            @if ($request->direct_manager_status == 'Rechazada' || $request->human_resources_status == 'Rechazada')
                                                <tr>

                                                    <td>{{ $request->id }}</td>
                                                    <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}
                                                    </td>
                                                    <td>{{ $request->type_request }}</td>
                                                    <td>{{ $request->payment }}</td>
                                                    <td>
                                                        @foreach ($rejectedDays as $rejectedDay)
                                                            @if ($request->id == $rejectedDay->requests_id)
                                                                {{ $rejectedDay->start }} ,

                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $request->reason }}</td>
                                                    <td><b> {{ $request->direct_manager_status }} </b></td>
                                                    <td><b>{{ $request->human_resources_status }}</b> </td>
                                                    <td>
                                                        {{-- <a style="width:100%"
                                                            href="{{ route('request.authorize.edit', ['request' => $request->id]) }}"
                                                            type="button" class="btn btn-primary">Detalles</a> --}}

                                                    </td>
                                                    {{-- <td>

                                                <form class="form-delete"
                                                    action="{{ route('request.destroy', ['request' => $request->id]) }}" method="POST">
                                                    @csrf
                                                    @method('delete')
                                                    <button style="width: 100%" type="submit" class="btn btn-danger">Borrar</button>
                                                </form>
                                            </td> --}}
                                                </tr>

                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
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
