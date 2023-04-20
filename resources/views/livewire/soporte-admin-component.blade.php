<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Asignación de tickets</h3>
        </div>
    </div>



    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipos de ticket</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                
                    @foreach ($users as $usuario)
                        <tr>
                            <th scope="row">{{$loop->iteration }}</th>
                            <td>{{ $usuario->name }}</td>
                            <td class="col-2">
                                @foreach ($usuario->asignacionCategoria as $categoria)
                                    {{ $categoria->name }}
                                @endforeach
                            </td>
                            <td class="col-2"><button type="button" class="btn btn-primary btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#ModalAsignacion"><i
                                        class="bi bi-pencil-fill" wire:click="verAsignacion({{ $usuario->id }})">Editar
                                        asignación</i>
                            </td>
                        </tr>
                      
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal asignacion --}}
    <div wire:ignore.self class="modal fade" id="ModalAsignacion" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar los tipos de tickets que recibe :
                        {{ $name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="align-items">

                    <div class="modal-body">
                        <div class=" input-group mb-3">

                            @foreach ($categorias as $categoria)
                                @php
                                    $check = false;
                                @endphp
                                @if ($user)
                                
                                    @foreach ($user->asignacionCategoria as $userCategory)
                                        @if ($categoria->id == $userCategory->id)
                                            @php
                                                $check = true;
                                                break;
                                            @endphp
                                        @endif

                                    @endforeach
                                @endif
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="{{ $categoria->id }}"
                                        id="flexCheckIndeterminate" wire:click='asignacion({{ $categoria->id }})'
                                        {{ $check ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $categoria->name }}">
                                        {{ $categoria->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>     

        window.addEventListener('asignacion_correcta', () => {
            
            toastr.success('categoria asignada correctamente')

        })
    </script>

</div>
