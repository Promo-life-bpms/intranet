@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Lista de usuarios</h3>
            <a href="{{ route('admin.users.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
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
                        <th scope="col">Rol</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td  class="text-center" >{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->employee->position)
                                    {{ $user->employee->position->department->name }}
                                @endif
                            </td>
                            <td>
                                @if ($user->employee->position)
                                    {{ $user->employee->position->name }}
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
                                {{ $user->employee->status == 1 ? 'Activo' : 'Inactivo' }}
                            </td>
                            <td>
                                @if ($user->roles)
                                    {{ $user->roles[0]->display_name }}
                                @endif
                            </td>
                            <td class="d-flex flex-wrap">
                                <a style="width: 80px" href="{{ route('admin.users.edit', ['user' => $user->id]) }}" type="button"
                                    class="btn btn-primary">Editar</a>
    
                                <form class="form-delete"
                                    action="{{ route('admin.users.destroy', ['user' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button style="width: 80px" type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
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
