<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Soporte solución</h3>
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
                    @foreach ($solucion as $tickets)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $tickets->name }}</td>
                            <td class="col-2">{{ $tickets->category->name }}</td>
                            <td class="col-2">

                                @if ($tickets->status->name == 'Resuelto')
                                    <div class="alert-sm alert-success rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @elseif ($tickets->status->name == 'Creado')
                                    <div class="alert-sm alert-info rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @elseif ($tickets->status->name == 'En proceso')
                                    <div class="alert-sm alert-primary rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @elseif ($tickets->status->name == 'Ticket Cerrado')
                                    <div class="alert-sm alert-warning rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @endif
                            </td>
                            <td>
                                <button onclick="atender({{ $tickets->id }}, {{ $tickets->status_id }})" type="button"
                                    class="btn btn-success btn-sm " wire:click="verTicket({{ $tickets->id }})"><i
                                        class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $solucion->links() }}
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                type="button" role="tab" aria-controls="home" aria-selected="true">Ticket</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="historial-tab" data-bs-toggle="tab" data-bs-target="#historial"
                                type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Historial</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="mensaje-tab" data-bs-toggle="tab" data-bs-target="#mensaje"
                                type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Mensajes</button>
                        </li>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"
                            wire:ignore.self>
                            <form method="POST">
                                @csrf
                                <p><span class="fw-bold ">Problema a resolver :</span> <span
                                        class="">{{ $name }}</span>
                                </p>

                                <p><span class="fw-bold ">Categoría :</span> <span
                                        class="">{{ $categoria }}</span></p>

                                <p><span class="fw-bold  ">Descripción:</span></p>

                                <div>
                                    <p>{!! $data !!}</p>
                                </div>

                                <hr>
                                {{-- <p><span class="fw-bold">Mensajes :</span></span></p>
                                @if ($mensaje)
                                    @foreach ($mensaje->mensajes as $mensajes)
                                        <span>{!! $mensajes->message !!}</span>
                                    @endforeach
                                @endif --}}
                                <div wire:ignore class="mb-3 text-input-mensaje">
                                    <label for="descripcion" class="form-label fw-bold">Solución</label>
                                    <textarea id="editorSolucion"cols="20" rows="3" class="form-control" name="description"></textarea>
                                </div>


                                @error('description')
                                    <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                                @enderror
                            </form>
                            @if ($status)
                                @if ($status->status_id == 4)
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                @else
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success"
                                            wire:click="guardarSolucion">Enviar</button>
                                        <div wire:loading.flex wire:target="guardarSolucion">
                                            Enviando
                                        </div>
                                    </div>
                                @endif

                            @endif

                        </div>

                        <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab"
                            wire:ignore.self>
                            @if ($historial)
                                <div class="card">
                                    <div class="card-header">
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-bordered table-hover">
                                            <tbody>
                                                @foreach ($historial->historial as $cambio)
                                                    <tr>
                                                        <td>
                                                            @if ($cambio->type == 'creado')
                                                                <div class="alert-sm rounded-3 alert-primary">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-check-circle-fill">{{ $cambio->type }}</i></strong>

                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @elseif ($cambio->type == 'edito')
                                                                <div class="alert-sm rounded-3 alert-warning">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-pencil-square">{{ $cambio->type }}</i></strong>

                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @elseif ($cambio->type == 'Mensaje')
                                                                <div class="alert-sm rounded-3 alert-info">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-envelope">{{ $cambio->type }}</i></strong>

                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @elseif ($cambio->type == 'status')
                                                                <div class="alert-sm alert-success">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-eye">Visto</i></strong>
                                                                        {{-- {{ $cambio->type }} --}}
                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @elseif ($cambio->type == 'solucion')
                                                                <div class="alert-sm rounded-3 alert-dark">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-check2-all">Ticket
                                                                                Cerrado</i></strong>
                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @elseif ($cambio->type == 'status_finished')
                                                                <div class="alert-sm rounded-3 alert-dark">
                                                                    <p class="mb-0"><strong><i
                                                                                class="bi bi-check2-all">Ticket
                                                                                Cerrado</i></strong>
                                                                        ({{ $cambio->created_at->diffForHumans() }})
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            {!! $cambio->data !!}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>


                        <div class="tab-pane fade" id="mensaje" role="tabpanel" aria-labelledby="mensaje-tab"
                            wire:ignore.self>
                            <p><span class="fw-bold">Mensajes :</span></span></p>
                            @if ($mensaje)
                                @foreach ($mensaje->mensajes as $mensajes)
                                    @if ($mensajes->user_id == auth()->user()->id)
                                        @if ($usuario)
                                            <div class="d-flex flex-row justify-content-end mb-2 pt-1">
                                                <span class="p-2 shadow bg-light text-dark rounded-3"><span>{!! $mensajes->message !!}</span><span>{{ $mensajes->created_at->diffForHumans() }}</span></span>
                                                <i class="bi bi-person-circle"></i>
                                            </div>
                                        @endif
                                    @else
                                        @if ($usuario)
                                            <div class="d-flex flex-row justify-content-start">
                                                <i class="bi bi-person-circle"></i>
                                                <span class="p-2 shadow bg-ligth rounded-3 text-dark"><span>{!! $mensajes->message !!}
                                                    </span>
                                                    <span>{{ $mensajes->created_at->diffForHumans() }}</span></span>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            <hr>
                            <div wire:ignore class="mb-3 text-input-mensaje">
                                {{-- <label for="descripcion" class="form-label fw-bold">Mensaje</label> --}}
                                <textarea id="editorMensaje"cols="20" rows="3" class="form-control" name="description"></textarea>
                            </div>
                            <div class="modal-footer">
                                @if ($status)
                                    @if ($status->status_id == 4)
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                    @else
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button" class="btn btn-success"
                                            wire:click="mensaje">Enviar</button>
                                        <div wire:loading.flex wire:target="mensaje">
                                            Enviando
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            let ckEditorSolucion, ckeEditorMensaje;

            //editor solucion
            ClassicEditor
                .create(document.querySelector('#editorSolucion'), {
                    removePlugins: ['MediaEmbed'],
                    extraPlugins: [MyCustomUploadAdapterPlugin],
                })
                .then(newEditor => {
                    ckEditorSolucion = newEditor;
                    // Escucha el evento 'change'
                    //para subir las imagenes y la data del ckeditor
                    ckEditorSolucion.model.document.on('change', () => {
                        const content = ckEditorSolucion.getData();
                        @this.description = content
                        console.log(content); // Imprime el contenido actualizado en la consola
                    });

                })
                .catch(error => {
                    console.error(error);
                });


            //editor mensaje
            ClassicEditor
                .create(document.querySelector('#editorMensaje'), {
                    removePlugins: ['MediaEmbed'],
                    extraPlugins: [MyCustomUploadAdapterPlugin],
                })
                .then(newEditor => {
                    ckeEditorMensaje = newEditor;
                    // Escucha el evento 'change'
                    //para subir las imagenes y la data del ckeditor
                    ckeEditorMensaje.model.document.on('change', () => {
                        const content = ckeEditorMensaje.getData();
                        @this.description = content
                        console.log(content); // Imprime el contenido actualizado en la consola
                    });

                })
                .catch(error => {
                    console.error(error);
                });


            class MyUploadAdapter {
                constructor(loader) {
                    this.loader = loader;
                }

                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            this._initRequest();
                            this._initListeners(resolve, reject, file);
                            this._sendRequest(file);
                        }));
                }

                abort() {
                    if (this.xhr) {
                        this.xhr.abort();
                    }
                }

                _initRequest() {
                    const xhr = this.xhr = new XMLHttpRequest();

                    xhr.open('POST', "{{ route('upload', ['_token' => csrf_token()]) }}", true);
                    xhr.responseType = 'json';
                }

                _initListeners(resolve, reject, file) {
                    const xhr = this.xhr;
                    const loader = this.loader;
                    const genericErrorText = `Couldn't upload file: ${ file.name }.`;

                    xhr.addEventListener('error', () => reject(genericErrorText));
                    xhr.addEventListener('abort', () => reject());
                    xhr.addEventListener('load', () => {
                        const response = xhr.response;

                        if (!response || response.error) {
                            return reject(response && response.error ? response.error.message : genericErrorText);
                        }

                        resolve(response);
                    });

                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', evt => {
                            if (evt.lengthComputable) {
                                loader.uploadTotal = evt.total;
                                loader.uploaded = evt.loaded;
                            }
                        });
                    }
                }

                _sendRequest(file) {
                    const data = new FormData();

                    data.append('upload', file);

                    this.xhr.send(data);
                }
            }

            function MyCustomUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
            }


            window.addEventListener('ticket_solucion', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Solución enviada correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })

                $('#ModalAgregar').modal('hide')

                ckEditorSolucion.setData("");

            });

            window.addEventListener('message', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'mensaje enviado correctamente',
                    showConfirmButton: false,
                    timer: 1500
                })

                $('#ModalAgregar').modal('hide')

                ckeEditorMensaje.setData("");

            });



            window.addEventListener('cargar', () => {

                if (ckEditorSolucion) {
                    ckEditorSolucion.destroy();
                    ClassicEditor
                        .create(document.querySelector('#editorSolucion'), {
                            removePlugins: ['MediaEmbed'],
                            extraPlugins: [MyCustomUploadAdapterPlugin],
                        })
                        .then(newEditor => {
                            ckEditorSolucion = newEditor;
                            // Escucha el evento 'change'
                            //para subir las imagenes y la data del ckeditor
                            ckEditorSolucion.model.document.on('change', () => {
                                const content = ckEditorSolucion.getData();
                                @this.description = content
                                console.log(content); // Imprime el contenido actualizado en la consola
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });

            window.addEventListener('cargar', () => {

                if (ckeEditorMensaje) {
                    ckeEditorMensaje.destroy();
                    ClassicEditor
                        .create(document.querySelector('#editorMensaje'), {
                            removePlugins: ['MediaEmbed'],
                            extraPlugins: [MyCustomUploadAdapterPlugin],
                        })
                        .then(newEditor => {
                            ckeEditorMensaje = newEditor;
                            // Escucha el evento 'change'
                            //para subir las imagenes y la data del ckeditor
                            ckeEditorMensaje.model.document.on('change', () => {
                                const content = ckeEditorMensaje.getData();
                                @this.mensajes = content
                                console.log(content); // Imprime el contenido actualizado en la consola
                            });
                        })
                        .catch(error => {
                            console.error(error);
                        });
                }
            });



            function atender(id, status_id) {

                if (status_id == 2 || status_id == 3 || status_id == 4) {
                    $('#ModalAgregar').modal('show')
                    // ckEditorSolucion.enableReadOnlyMode( 'editorMensaje' )

                } else {
                    Swal.fire({
                        title: 'Quieres dar solucion a este ticket?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            let resultado = @this.enProceso(id)
                            $('#ModalAgregar').modal('show')

                            toastr.success("Ticket en proceso")
                        }
                    })
                }

            }
        </script>
    </div>
