@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Managers</h3>
            <a href="{{ route('admin.manager.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%" scope="col"># </th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Departamento</th>
                        <th style="width: 20%" scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($managers as $manager)
                        <tr>
                          {{--   <td>{{ $manager->id }}</td> --}}
                          <td>{{ $loop->iteration }}</td>
                            <td>{{ $manager->user->name . ' ' . $manager->user->lastname }}
                            </td>
                            <td>{{ $manager->department->name }}</td>
                            <td class="d-flex flex-wrap">
                                <a  style="width:80px;" href="{{ route('admin.manager.edit', ['manager' => $manager->id]) }}" type="button"
                                    class="btn btn-primary">Editar</a>
                                <form class="form-delete"
                                    action="{{ route('admin.manager.destroy', ['manager' => $manager->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button style="width:80px;" type="submit" class="btn btn-danger">Borrar</button>
                                </form>
                            </td>
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
