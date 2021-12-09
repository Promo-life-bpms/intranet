@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Lista de usuarios</h3>
            <a href="{{ route('admin.user.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Area</th>
                    <th scope="col">Puesto</th>
                    <th scope="col">Empresas</th>
                    <th scope="col">Ingreso</th>
                    <th scope="col">Jefe Directo</th>
                    <th scope="col">Status</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <th>{{ $user->id }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if (count($user->employee->positions) > 0)
                                {{ $user->employee->positions[0]->department->name }}
                            @endif
                        </td>
                        <td>
                            @if (count($user->employee->positions) > 0)
                                {{ $user->employee->positions[0]->name }}
                            @endif
                        </td>
                        <td>
                            @foreach ($user->employee->companies as $company)
                                {{ $company->name_company }}
                            @endforeach
                        </td>
                        <td>{{ $user->employee->date_admission }}</td>
                        <td>
                            @if ($user->employee->jefeDirecto)
                                {{ $user->employee->jefeDirecto->user->name }}
                            @endif
                        </td>
                        <td>
                            @if ($user->employee->jefeDirecto)
                                {{ $user->employee->status == 1 ? 'Activo' : 'Inactivo' }}
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.user.edit', ['user' => $user->id]) }}" type="button"
                                class="btn btn-primary">EDITAR</a>

                            <form class="form-delete" action="{{ route('admin.user.destroy', ['user' => $user->id]) }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">BORRAR</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{$users ->links() }}
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
