<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Soporte</h3>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ModalAgregar">
                <i class="bi bi-plus-square">Crear solicitud</i>
            </button>
        </div>
    </div>



    <div class="card-body">

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Status</th>
                        <th scope="col">Acciones</th>

                    </tr>
                </thead>
                <tbody>

                    @foreach ($tickets as $ticket)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $ticket->name }}</td>
                            <td class="col-2">{{ $ticket->category->name }}</td>
                            <td class="col-2">
                                @if ($ticket->status->name == 'Resuelto')
                                    {{-- <span class="badge bg-danger">{{ $ticket->status->name }}</span> --}}
                                    <div class="alert-sm alert-success rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @elseif ($ticket->status->name == 'Creado')
                                    {{-- <span class="badge bg-primary">{{ $ticket->status->name }}</span> --}}
                                    <div class="alert-sm alert-info rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @elseif ($ticket->status->name == 'En proceso')
                                    <div class="alert-sm alert-primary rounded-3" role="alert">
                                        {{ $ticket->status->name }}</div>
                                @endif

                            </td>
                            <td>

                                <button type="button" class="btn btn-success btn-sm " data-bs-toggle="modal"
                                    data-bs-target="#ModalVer" wire:click="verTicket({{ $ticket->id }})"><i
                                        class="bi bi-eye"></i></button>

                                @if ($ticket->status->name == 'Creado' || $ticket->status->name == 'En proceso')
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ModalEditar" wire:click="editarTicket({{ $ticket->id }})"><i
                                            class="bi bi-pencil"></i></button>

                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="finalizar({{ $ticket->id }})"><i
                                            class="bi bi-check-square"></i></button>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>

        </div>
    </div>




    <!-- Modal Agregar-->

    <div wire:ignore.self class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">


                    <form enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="Problema" class="form-label">Problema a resolver</label>
                            <input type="text"
                                class="form-control input-lg @error('name') is-invalid @enderror "placeholder="ingresa el problema a resolver"
                                name="name" wire:model="name" value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Problema" class="form-label">Categoría</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01">Categoría</label>
                                <select wire:model="categoria" name="categoria"
                                    class="form-select @error('categoria') is-invalid @enderror"
                                    id="inputGroupSelect01">
                                    <option selected value="">Seleccionar</option>
                                    @foreach ($categorias as $categoriaa)
                                        <option value="{{ $categoriaa->id }}">{{ $categoriaa->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div wire:ignore class="mb-3 text-input-crear">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea wire:model="data" id="editor" cols="20" rows="3" class="form-control" name="data"></textarea>
                        </div>
                        @error('data')
                            <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                        @enderror
                    </form>


                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" wire:click='guardar'>Guardar</button>
                    <div wire:loading.flex wire:target="guardar">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal editar --}}
    <div wire:ignore.self class="modal fade" id="ModalEditar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editar ticktet</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="Problema" class="form-label">Problema a resolver</label>
                            <input type="text" class="form-control input-lg @error('name') is-invalid @enderror "
                                placeholder="ingresa el problema a resolver" name="name" wire:model="name"
                                value="{{ old('name') }}">
                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="Problema" class="form-label">Categoría</label>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect01">Categoría</label>
                                <select wire:model="categoria" name="categoria"
                                    class="form-select @error('categoria') is-invalid @enderror"
                                    id="inputGroupSelect01">
                                    <option value="" selected>Seleccionar</option>
                                    @foreach ($categorias as $categoriaa)
                                        <option value="{{ $categoriaa->id }}">{{ $categoriaa->name }}</option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>


                        </div>

                        <div wire:ignore class="mb-3 text-input-editar">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea wire:model="data" id="editorEditar" cols="20" rows="3" class="form-control" name="data"></textarea>
                            @error('data')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success"
                        wire:click="guardarEditar({{ $ticket_id }})">Guardar</button>
                    <div wire:loading.flex wire:target="guardarEditar">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal ver --}}
    <div wire:ignore.self class="modal fade" id="ModalVer" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel">Detalles ticket</h1>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Detalles en ticket</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial"
                                type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Historial</button>
                        </li>
                    </ul>
                </div>
                <div class="modal-body">

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab" wire:ignore.self>
                            <p><span class="fw-bold">Problema a resolver :</span> <span>{{ $name }}</span></p>

                            <p><span class="fw-bold">Categoría :</span> <span>{{ $categoria }}</span></p>

                            <p><span class="fw-bold">Descripción :</span></p>

                            <div class="text-mostrar">
                                <p>{!! $data !!}</p>
                            </div>
                            <hr>
                            <p><span class="fw-bold">Solución:</span></p>
                            @if ($ticket_solucion)
                                @foreach ($ticket_solucion->solution as $solucion)
                                    {!! $solucion->description !!}
                                @endforeach
                            @endif
                            <hr>
                            {{-- Editor Mensaje --}}
                            <div wire:ignore class="mb-3 text-input-mensaje">
                                <label for="descripcion" class="form-label">Enviar mensaje</label></label>
                                <textarea id="editorMensaje" cols="20" rows="3" class="form-control" name="message"></textarea>
                                @error('message')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab"
                            wire:ignore.self>Historial
                        </div>
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" wire:click="enviarMensaje">Guardar</button>
                    <div wire:loading.flex wire:target="enviarMensaje">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        let ckEditorCreate, ckEditorEdit, ckEditorMensaje;
        ClassicEditor
            .create(document.querySelector('#editor'), {
                removePlugins: ['Link', 'CKFinder']
            })
            .then(newEditor => {
                ckEditorCreate = newEditor;


            })
            .catch(error => {
                console.error(error);
            });
        document.addEventListener("DOMContentLoaded", () => {
            const editor = document.querySelector('.text-input-crear .ck-editor__editable');
            //Escuchar el evento key?
            editor.addEventListener('keyup', () => {
                let texto = ckEditorCreate.getData();
                console.log(texto);
                // Funcion que se ejecute en el evento key_up
                //Obtener el elemento .ck-editor en una variable

                @this.data = texto
                // Obtener el html que tiene esa etiqueta
            })
            const editorUpdate = document.querySelector('.text-input-editar .ck-editor__editable');
            //Escuchar el evento key?
            editorUpdate.addEventListener('keyup', () => {
                let texto = editorUpdate.innerHTML;
                //let texto = editorUpdate.getData();
                console.log(texto);
                // Funcion que se ejecute en el evento key_up
                //Obtener el elemento .ck-editor en una variable

                @this.data = texto
                // Obtener el html que tiene esa etiqueta
            })
            const editorMessage = document.querySelector('.text-input-mensaje .ck-editor__editable');
            //Escuchar el evento key?
            editorMessage.addEventListener('keyup', () => {
                let texto = editorMessage.innerHTML;


                @this.mensaje = texto

            })

        });




        ClassicEditor
            .create(document.querySelector('#editorEditar'), {

            })
            .then(newEditor => {
                ckEditorEdit = newEditor;


            })
            .catch(error => {
                console.error(error);
            });


        ClassicEditor
            .create(document.querySelector('#editorMensaje'), {

            })
            .then(newEditor => {
                ckEditorEdit = newEditor;


            })
            .catch(error => {
                console.error(error);
            });








        document.addEventListener("mostrar_data", () => {

            ckEditorEdit.setData(@this.data);
            ckEditorVer.setData(@this.data);


        });

        document.addEventListener("borrar", () => {
            ClassicEditor.remove('.ck ck-reset_all.ck-widget__type-around');
            ClassicEditor.remove(ckEditorVer);
        });




        window.addEventListener('ticket_success', () => {
            Swal.fire({
                icon: 'success',
                title: 'Ticket enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#ModalAgregar').modal('hide')

            ckEditorCreate.setData("");

        });

        window.addEventListener('editar', () => {
            Swal.fire({

                icon: 'success',
                title: 'Ticket editado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#ModalEditar').modal('hide')
            ckEditorEdit.setData("");


        });

        window.addEventListener('Mensaje', () => {
            Swal.fire({

                icon: 'success',
                title: 'Mensaje enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#ModalMensaje').modal('hide')
            ckEditorMensaje.setData("");


        });


        function finalizar(id) {

            Swal.fire({
                title: 'Quieres finalizar el ticket?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Finalizar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let resultado = @this.finalizarTicket(id)
                    Swal.fire(
                        'Finalizado ',
                        'El ticket a sido finalizado',
                        'success'
                    )
                } else {
                    return;
                }

            })

        }
    </script>



</div>
