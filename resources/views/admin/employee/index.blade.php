@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Empleados</h3>
            <!-- <a href="{{ route('admin.employee.create') }}" type="button" class="btn btn-success">Agregar</a> -->
        </div>
    </div>
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># </th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellidos</th>
                    <th scope="col">Cumpleaños</th>
                    <th scope="col">Ingreso</th>
                    <th scope="col">Status</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach  ($employees as $employee)
                    <tr>
                        <td>{{ $employee->id }}</td>
                        <td>{{ $employee->user->name }}</td>
                        <td>{{ $employee->nombre }}</td>
                        <td>{{ $employee->paterno . ' ' . $employee->materno }}</td>
                        <td>{{ $employee->fecha_cumple }}</td>
                        <td>{{ $employee->fecha_ingreso }}</td>
                        <td>{{ $employee->status }}</td>
                        <td>
                            <a href="{{ route('admin.employee.edit', ['employee' => $employee->id]) }}" type="button"
                                class="btn btn-primary">Editar</a>
                            <form class="form-delete"
                                action="{{ route('admin.employee.destroy', ['employee' => $employee->id]) }}"
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
        {{$employees ->links() }}
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
