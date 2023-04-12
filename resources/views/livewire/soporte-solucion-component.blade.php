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
                            <th scope="row">{{ $tickets->id }}</th>
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

                                <button onclick="finalizar({{ $tickets->id }})" type="button"
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Solución Tickets</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form>
                        @csrf
                        <p><span class="fw-bold">Problema a resolver :</span> <span>{{ $name }}</span></p>

                        <p><span class="fw-bold">Categoría :</span> <span>{{ $categoria }}</span></p>

                        <p><span class="fw-bold">Descripción:</span></p>

                        <div class="text-mostrar">
                            <p>{!! $data !!}</p>
                        </div>

                        <hr>
                        <p><span class="fw-bold">Historial</span></p>
                        <hr>
                        <div wire:ignore class="mb-3 text-input-crear">
                            <label for="descripcion" class="form-label">Solución</label>
                            <textarea id="editor" wire:model="description" cols="20" rows="3" class="form-control" name="description"></textarea>
                            @error('description')
                            <p class="text-danger fz-1 font-bold m-0">{{ $message }}</p>
                        @enderror

                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" wire:click="guardarSolucion">Guardar</button>
                    <div wire:loading.flex wire:target="guardarSolucion">
                        Guardando
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        let ckEditorCreate, ckEditorEdit, ckEditorVer;
        ClassicEditor
            .create(document.querySelector('#editor'))
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

            ckEditorCreate.setData("");

        });

     


        function finalizar(id) {

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
                   /*  Swal.fire({

                        icon: 'success',
                        title: 'Ticket en proceso',
                        showConfirmButton: false,
                        timer: 1500
                    }) */
                    toastr.success("Ticket en proceso")
                } else {
                   
                    // let status = @this.enProceso(id)==2
                       $('#ModalAgregar').modal('hide')
                     return;
                    

                }

            })

        }
    </script>
</div>
