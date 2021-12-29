@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Directorio telefonico y correos</h3>
            {{-- @role('systems')
                <a href="{{ route('admin.contacts.create') }}" type="button" class="btn btn-success">Agregar</a>
            @endrole --}}
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Numero</th>
                    <th scope="col">Promolife</th>
                    <th scope="col">BH-Trademarket</th>
                    <th scope="col">Trademarket</th>
                    <th scope="col">PormoDreams</th>
                    @role('systems')
                        <th scope="col">Opciones</th>
                    @endrole
                </tr>
            </thead>

            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $contact->user->name }}</td>
                        <td>{{ $contact->num_tel }}</td>
                        <td>{{ $contact->correo1 }}</td>
                        <td>{{ $contact->correo2 }}</td>
                        <td>{{ $contact->correo3 }}</td>
                        <td>{{ $contact->correo4 }}</td>
                        @role('systems')
                            <td>
                                <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                    type="button" class="btn btn-primary">EDITAR</a>
                                {{-- <form class="form-delete"
                                    action="{{ route('admin.contact.destroy', ['contact' => $contact->id]) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button style="width: 100%;" type="submit" class="btn btn-danger">BORRAR</button>
                                </form>  --}}
                            </td>
                        @endrole
                    </tr>
                @endforeach

            </tbody>
        </table>
        {{ $contacts->links() }}
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
