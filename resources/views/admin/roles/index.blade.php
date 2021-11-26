@extends('layouts.app')

@section('title')
    <h3>Roles</h3>
@endsection

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h4 class="card-title">Listado de Roles</h4>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">AGREGAR NUEVO</a>
        </div>
    </div>
    <div class="card-body">
        <table class="table text-center" id="tableRoles">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <th>{{ $role->id }}</th>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a href="{{ route('admin.roles.edit', ['role' => $role->id]) }}" type="button"
                                class="btn btn-primary">EDITAR</a>

                            <form action="{{ route('admin.roles.destroy', ['role' => $role->id]) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">BORRAR</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('scripts')
    <script>
        // Jquery Datatable
        let jquery_datatable = $("#tableRoles").DataTable()
    </script>
@stop
