@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Reportes por Periodo (Dias de solicitud)</h3>

            <div class="d-flex justify-content-end">
            <a style="margin-left: 20px;" href=" {{ route('request.export2') }} " type="button"
                class="btn btn btn-success">Exportar Excel</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Solicitante</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Pago</th>
                        <th scope="col">Fechas ausencia</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Vacaciones disponibles</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->employee->user->name . ' ' . $request->employee->user->lastname }}</td>
                            <td>{{ $request->type_request }}</td>
                            <td>{{ $request->payment }}</td>
                            <td>
                                @foreach ($requestDays as $requestDay)
                                    @if ($request->id == $requestDay->requests_id)
                                        {{ $requestDay->start  }} ,
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->vacations->days_availables  }} </td>
                        </tr>
                    @endforeach
    
                </tbody>
            </table>
        </div>
        

    </div>


    <div class="modal fade" id="modalBusqueda" tabindex="-1" aria-labelledby="modalBusquedaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBusquedaLabel">Realizar Búsqueda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'request.filter']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('inicio', 'Fecha de inicio') !!}
                            {!! Form::date('inicio',null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('inicio')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                        </div>
                        <div class="col">
                            {!! Form::label('fin', 'Fecha de fin') !!}
                            {!! Form::date('fin',null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                            @error('fin')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Buscar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
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
