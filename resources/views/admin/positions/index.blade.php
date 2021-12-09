@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Puestos</h3>
                <a href="{{ route('admin.position.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Departamento</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($positions  as $position)
                    <tr>
                        <td>{{ $position->id }}</td>
                        <td>{{ $position->name }}</td>
                        <td>{{ $position->department->name }}</td>
                        <td>
                            <a style="width: 100%;" href="{{ route('admin.position.edit', ['position' => $position->id]) }}"
                                type="button" class="btn btn-primary">EDITAR</a>
                            <form class="form-delete"
                                action="{{ route('admin.position.destroy', ['position' => $position->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button style="width: 100%;" type="submit" class="btn btn-danger">BORRAR</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
            {{ $positions ->links() }}
            
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
