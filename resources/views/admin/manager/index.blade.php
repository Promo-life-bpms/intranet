@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Encargados de Departamentos</h3>
{{--                 <a href="{{ route('admin.department.create') }}" type="button" class="btn btn-success">Agregar</a>
 --}}        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Empleado</th>
                    <th scope="col">Departamento</th>
                </tr>
            </thead>

            <tbody>
                {{-- @foreach ($departments  as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->name }}</td>
                        <td>
                            <a style="width: 100%;" href="{{ route('admin.department.edit', ['department' => $department->id]) }}"
                                type="button" class="btn btn-primary">EDITAR</a>
                            <form class="form-delete"
                                action="{{ route('admin.department.destroy', ['department' => $department->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button style="width: 100%;" type="submit" class="btn btn-danger">BORRAR</button>
                            </form>
                        </td>
                    </tr>
                @endforeach --}}

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
