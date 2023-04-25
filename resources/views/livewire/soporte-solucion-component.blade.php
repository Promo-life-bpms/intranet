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
                                    {{-- <span class="badge bg-danger">{{ $ticket->status->name }}</span> --}}
                                    <div class="alert-sm alert-success rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @elseif ($tickets->status->name == 'Creado')
                                    <div class="alert-sm alert-info rounded-3" role="alert">
                                        {{ $tickets->status->name }}</div>
                                @elseif ($tickets->status->name == 'En proceso')
                                    <div class="alert-sm alert-primary rounded-3" role="alert">
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
                    {{-- <h1 class="modal-title fs-5" id="exampleModalLabel">Solución Tickets</h1> --}}
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
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- <form>
                        @csrf
                        <p><span class="fw-bold">Problema a resolver :</span> <span>{{ $name }}</span></p>

                        <p><span class="fw-bold">Categoría :</span> <span>{{ $categoria }}</span></p>

                        <p><span class="fw-bold">Descripción:</span></p>

                        <div>
                            <p>{!! $data !!}</p>
                        </div>

                        <hr>
                        <p><span class="fw-bold">Mensajes :</span></span></p>
                        @if ($mensaje)
                            @foreach ($mensaje->mensajes as $mensajes)
                                <span>{!! $mensajes->message !!}</span>
                            @endforeach
                        @endif
                        <hr>
                        <div wire:ignore class="mb-3 text-input-crear">
                            <label for="descripcion" class="form-label fw-bold">Solución</label>
                            <textarea id="editor"cols="20" rows="3" class="form-control" name="description"></textarea>
                        </div>
                        @error('description')
                            <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                        @enderror
                    </form> --}}

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"
                            wire:ignore.self>
                            <form>
                                @csrf
                                <p><span class="fw-bold">Problema a resolver :</span> <span>{{ $name }}</span>
                                </p>

                                <p><span class="fw-bold">Categoría :</span> <span>{{ $categoria }}</span></p>

                                <p><span class="fw-bold">Descripción:</span></p>

                                <div>
                                    <p>{!! $data !!}</p>
                                </div>

                                <hr>
                                <p><span class="fw-bold">Mensajes :</span></span></p>
                                @if ($mensaje)
                                    @foreach ($mensaje->mensajes as $mensajes)
                                        <span>{!! $mensajes->message !!}</span>
                                    @endforeach
                                @endif
                                <hr>
                                <div wire:ignore class="mb-3 text-input-crear">
                                    <label for="descripcion" class="form-label fw-bold">Solución</label>
                                    <textarea id="editor"cols="20" rows="3" class="form-control" name="description"></textarea>
                                </div>
                                @error('description')
                                    <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                                @enderror
                            </form>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-success" wire:click="guardarSolucion">Enviar</button>
                                <div wire:loading.flex wire:target="guardarSolucion">
                                    Enviando
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="historial" role="tabpanel" aria-labelledby="historial-tab"
                            wire:ignore.self>Historial
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script>
        let ckEditorSolucion;
        ClassicEditor
            .create(document.querySelector('#editor'), {
                removePlugins: ['Heading']
            })
            .then(newEditor => {
                ckEditorSolucion = newEditor;


            })
            .catch(error => {
                console.error(error);
            });
        document.addEventListener("DOMContentLoaded", () => {
            const editor = document.querySelector('.text-input-crear .ck-editor__editable');
            //Escuchar el evento key?
            editor.addEventListener('keyup', () => {
                let texto = ckEditorSolucion.getData();
                @this.description = texto
                // Obtener el html que tiene esa etiqueta
            })

        })


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

        function atender(id, status_id) {

            if (status_id == 2) {
                $('#ModalAgregar').modal('show')
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
