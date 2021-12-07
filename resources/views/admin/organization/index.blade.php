@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Organizacion</h3>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="shadow-sm p-3 mb-5 rounded">
                    <div class="d-flex justify-content-between">
                        <h4>Puesto</h4>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPuesto">
                            Agregar
                        </button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Area</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($positions as $position)
                                <tr>
                                    <td>{{ $position->id }}</td>
                                    <td>{{ $position->name }}</td>
                                    <td>{{ $position->department->name }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('admin.position.edit', ['position' => $position->id]) }}"
                                            type="button" class="btn btn-primary">EDITAR</a>
                                        <form class="form-delete"
                                            action="{{ route('admin.position.destroy', ['position' => $position->id]) }}"
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
                </div>
            </div>
            <div class="col-md-4">
                <div class="shadow-sm p-3 mb-5 rounded">
                    <div class="d-flex justify-content-between">
                        <h4>Empresas</h4>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalEmpresa">
                            Agregar
                        </button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($organizations as $organization)
                                <tr>
                                    <td>{{ $organization->id }}</td>
                                    <td>{{ $organization->name_company }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('admin.organization.edit', ['organization' => $organization->id]) }}"
                                            type="button" class="btn btn-primary">E</a>
                                        <form class="form-delete"
                                            action="{{ route('admin.organization.destroy', ['organization' => $organization->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">D</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="shadow-sm p-3 mb-5 rounded">
                    <div class="d-flex justify-content-between">
                        <h4>Departamentos</h4>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#modalDepartamento">
                            Agregar
                        </button>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $department->id }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td class="d-flex justify-content-center">
                                        <a href="{{ route('admin.department.edit', ['department' => $department->id]) }}"
                                            type="button" class="btn btn-primary">E</a>
                                        <form class="form-delete"
                                            action="{{ route('admin.department.destroy', ['department' => $department->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger">D</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalEmpresa" tabindex="-1" aria-labelledby="modalEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEmpresaLabel">Agregar Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'admin.organization.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('name_company', 'Nombre empresa') !!}
                            {!! Form::text('name_company', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la empresa']) !!}
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col">
                            {!! Form::label('description_company', 'Direccion de la empresa') !!}
                            {!! Form::text('description_company', null, ['class' => 'form-control', 'placeholder' => 'Ingrese la direccion de la empresa']) !!}
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    {!! Form::submit('Crear Empresa', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalDepartamento" tabindex="-1" aria-labelledby="modalDepartamentoLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDepartamentoLabel">Crear departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'admin.department.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('name', 'Nombre departamento') !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del departamento']) !!}
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    {!! Form::submit('Crear departamento', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalPuesto" tabindex="-1" aria-labelledby="modalPuestoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPuestoLabel">Crear Puesto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'admin.position.store']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            {!! Form::label('name', 'Nombre del puesto') !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre del puesto']) !!}
                        </div>
                        <div class="col">
                            {!! Form::label('department', 'Nombre del departamento') !!}
                            <select name="department" class="form-control">
                                <option value="" disabled selected>Seleccione..</option>
                                @foreach ($departments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    {!! Form::submit('Crear Puesto', ['class' => 'btn btn-primary']) !!}
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
