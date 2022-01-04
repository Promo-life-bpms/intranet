@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Dias no laborales</h3>
            <a href="{{ route('admin.noworkingdays.create') }} " type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"># </th>
                    <th scope="col">Dia</th>
                    <th scope="col">Festividad</th>
                    <th scope="col">Empresa</th>
                    <th style="width: 20%" scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($noworkingdays as $noworkingday)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $noworkingday->day }}</td>
                        <td>{{ $noworkingday->reason }}</td>
                        <td>{{ $noworkingday->company->name_company}}</td>
                        <td class="d-flex flex-wrap">
                            <a style="width: 80px" href="{{ route('admin.noworkingdays.edit', ['noworkingday' => $noworkingday->id]) }}" type="button"
                                class="btn btn-primary">Editar</a>
                            <form class="form-delete"
                                action="{{ route('admin.noworkingdays.delete', ['noworkingday' => $noworkingday->id]) }}" method="POST">
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
