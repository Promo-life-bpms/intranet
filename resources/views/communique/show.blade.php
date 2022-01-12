@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Gestionar comunicados</h3>
            <a href="{{ route('communiques.create') }}" type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col"># </th>
                        <th scope="col">Título</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Archivo</th>
                        <th style="width: 50%" scope="col">Descripcion</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($communiques as $communique)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $communique->title }}</td>
                            <td>
                                <img style="width: 100px; heigth:100px;" src="{{ asset($communique->image )}}" alt="">
                                
                            </td>
    
                            <td>
                                @if ($communique->file ==null)
                                    Sin archivo 
                                @else
                                    <a href="{{ asset($communique->file )}}" target="_blank">Ver archivo guardado</a>
                                @endif
                            </td>
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
