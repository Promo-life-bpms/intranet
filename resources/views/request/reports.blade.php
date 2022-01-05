@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Generar reportes</h3>

            
            <a href="{{-- {{ route('request.edit', ['request' => $request->id]) }} --}}" type="button"
                class="btn btn btn-success">Exportar Excel</a>
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
