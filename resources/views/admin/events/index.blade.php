@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Eventos</h3>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.events.showEvents') }} " type="button" class="btn btn-success" style="margin-right: 10px;">Ver calendario de eventos</a>
                <a href="{{ route('admin.events.create') }} " type="button" class="btn btn-success">Agregar</a>
            </div>

        </div>
    </div>
    <div class="card-body">

        <table class="table display nowrap" >
            <thead>
                <tr>
                    <th class="number" scope="col"># </th>
                    <th style="max-width: 20%" scope="col">Evento</th>
                    <th style="width: 40%"  scope="col">Descripcion</th>
                    <th style="max-width: 10%"  scope="col">Fecha Inicio</th>
                    <th style="max-width: 10%"  scope="col">Creador de solicitud</th>
                    <th style="max-width: 10%"  scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach  ($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->description }}</td>
                        <td>{{ $event->start }}</td>
                        <td>{{ $event->users->name.' '.$event->users->lastname }}</td>
{{--                         <td>{{ $event->end }}</td>
 --}}                    <td>
                            <a href="{{ route('admin.events.edit', ['event' => $event->id]) }}" type="button"
                                class="btn btn-primary">Editar</a>
                            <form class="form-delete"
                                action="{{ route('admin.events.destroy', ['event' => $event->id]) }}"
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
@section('styles')

<style>
    table { table-layout: fixed; }
    table th, table td { overflow: hidden; }
    .number{width: 4%;}

</style>
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
