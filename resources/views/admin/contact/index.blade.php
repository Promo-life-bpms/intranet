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
        <div class="form-group col-6">
            {!! Form::label('department_id', 'Departamento') !!}
            {!! Form::select('department_id', $departments, null, ['class' => 'form-control', 'placeholder' => 'Selecciona Departamento']) !!}
            @error('department_id')
                <small>
                    <font color="red"> *Este campo es requerido* </font>
                </small>
                <br>
            @enderror
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Numero</th>
                        <th scope="col">Promolife</th>
                        <th scope="col">BH-Trademarket</th>
                        <th scope="col">Trademarket</th>
                        <th scope="col">PormoDreams</th>
                        @role('systems')
                            <th style="width: 10%" scope="col">Opciones</th>
                        @endrole
                    </tr>
                </thead>
    
                <tbody>
                    @foreach ($contacts as $contact)
                        <tr name="contact">
                            <td>{{ $loop->iteration }}</td>
                            <td name="contacts" >{{ $contact->user->name }}</td>
                            <td name="name">{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td> 
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
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
        </div>
        
        {{ $contacts->links() }}
    </div>
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

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

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('select[name="department_id"]').on('change', function() {
            var id = jQuery(this).val();
            if (id) {
                jQuery.ajax({
                    url: '/contact/getContacts/' + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        jQuery('tr[name="contact"]').empty();
                        jQuery.each(data, function(key, value) {
                            $('tr[name="contact').append('<td>' + key '</td>');
                        });
                    }
                });
            } else {
                $('td[name="contacts"]').empty();
            }
        });
    });
</script>

@stop
