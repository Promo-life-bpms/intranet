@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Comunicados</h3>
            <a href="{{ route('communique.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># </th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Paterno</th>
                    <th scope="col">Materno</th>
                    <th scope="col">Solicitud</th>
                    <th scope="col">Fecha de solicitud</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Especificacion </th>
                    <th scope="col">Motivo</th>
                    <th scope="col">Dia de Inicio </th>
                    <th scope="col">Dia de Fin</th>
                    <th scope="col">status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr>
                        <td>{{ $request->id }}</td>
                        <td>{{ $request->nombre_soli }}</td>
                        <td>{{ $request->fecha_soli }}</td>
                        <td>{{ $request->tipo_soli }}</td>
                        <td>{{ $request->dias_soli }}</td>
                        <td>{{ $request->especificacion_soli }}</td>
                        <td>{{ $request->motivo_soli }}</td>
                        <td>{{ $request->status }}</td>
                        <td>
                            <a href="{{ route('communique.edit', ['communique' => $communique->id]) }}" type="button"
                                class="btn btn-primary">Editar</a>

                            <form class="form-delete" action="{{ route('communique.destroy', ['communique' => $communique->id]) }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Borrar</button>
                            </form>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

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
