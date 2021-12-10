@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Comunicados</h3>
            <a href="{{ route('communiques.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th scope="col"># </th>
                    <th scope="col">Titullo</th>
                    <th scope="col">URL Imagen</th>
                    <th scope="col">Descripcion</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($communiques as $communique)
                    <tr>
                        <td>{{ $communique->id }}</td>
                        <td>{{ $communique->title }}</td>
                        <td>{{ $communique->images }}</td>
                        <td>{{ $communique->description }}</td>
                        <td>
                            <a href="{{ route('communiques.edit', ['communique' => $communique->id]) }}" type="button"
                                class="btn btn-primary">Editar</a>

                            <form class="form-delete" action="{{ route('communiques.destroy', ['communique' => $communique->id]) }}"
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
