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
        aria-hidden="true" data-bs-backdrop="static">
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
                                @foreach ($historial->historial as $cambio)
                                    @if ($cambio->type == 'creado')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check-circle-fill"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card ">

                                                        <div class="card-body rounded-3  shadow " id="historial">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})
                                                            </div>
                                                            <h4 class="card-title  text-green">{{ $cambio->type }}
                                                            </h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'edito')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-pencil-square"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body rounded-3  shadow " id="historial">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">{{ $cambio->type }}</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'Mensaje')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-envelope"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($cambio->user_id == auth()->user()->id)
                                                    <div class="col py-2">
                                                        <div class="card">
                                                            <div class="card-body rounded-3  shadow " id="historial">
                                                                <div class="float-end text-dark">
                                                                    ({{ $cambio->created_at->diffForHumans() }})</div>
                                                                <h4 class="card-title text-green">{{ $cambio->type }}
                                                                    de {{ auth()->user()->name }}</h4>
                                                                <p class="card-text text-dark">{!! $cambio->data !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col py-2">
                                                        <div class="card">
                                                            <div class="card-body rounded-3  shadow " id="historial">
                                                                <div class="float-end text-dark">
                                                                    ({{ $cambio->created_at->diffForHumans() }})</div>
                                                                <h4 class="card-title text-green">{{ $cambio->type }}
                                                                    de {{ $usuario->name }} </h4>
                                                                <p class="card-text text-dark">{!! $cambio->data !!}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'status')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-eye"></i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body rounded  shadow ">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">Visto</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'solucion')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check2-all">
                                                            </i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">

                                                        <div class="card-body shadow">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">{{ $cambio->type }}</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'status_finished')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-check2-all">
                                                            </i></span>
                                                    </h5>
                                                    <div class="row h-50">
                                                        <div class="col border-end">
                                                            &nbsp;
                                                        </div>
                                                        <div class="col">
                                                            &nbsp;
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col py-2">
                                                    <div class="card">
                                                        <div class="card-body  shadow ">
                                                            <div class="float-end text-dark">
                                                                ({{ $cambio->created_at->diffForHumans() }})</div>
                                                            <h4 class="card-title text-green">Ticket Cerrado</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif

                        </div>
                        <div class="tab-pane fade" id="mensaje" role="tabpanel" aria-labelledby="mensaje-tab"
                            wire:ignore.self >
                            @if ($mensaje)
                                @foreach ($mensaje->mensajes as $mensajes)
                                    @if ($mensajes->user_id == auth()->user()->id)
                                        <div class="d-flex flex-row justify-content-end mb-3 pt-3">
                                            <span
                                                class=" p-2 shadow bg-light text-dark rounded-3"><span>{!! $mensajes->mensaje !!}</span><span>{{ $mensajes->created_at->diffForHumans() }}</span></span>
                                            <i class="bi bi-person-circle"></i>
                                        </div>
                                    @else
                                        <div class="d-flex flex-row justify-content-start">
                                            <i class="bi bi-person-circle"></i>
                                            <span class="p-2 shadow bg-ligth rounded-3 text-dark"><span
                                                    class="fw-bold">{{ $mensajes->usuarios->name }}</span><span>{!! $mensajes->mensaje !!}
                                                </span>
                                                <span>{{ $mensajes->created_at->diffForHumans() }}</span></span>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <hr>
                            <div wire:ignore class="mb-3 text-input-mensaje">
                                <textarea id="editorMensaje"cols="20" rows="3" class="form-control" name="mensaje"></textarea>
                            </div>
                            @error('mensajes')
                                <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                            @enderror


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
                  
                })
                .then(newEditor => {
                    ckeEditorMensaje = newEditor;


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

                // $('#ModalAgregar').modal('hide')

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
