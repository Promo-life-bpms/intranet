<div>
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Asignacion de tickets</h3>
        </div>
    </div>



    <div class="card-body">

        <div class="table-responsive">
            <table class="table text-center">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Tipos de ticket</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <th scope="row"></th>
                        <td></td>
                        <td class="col-2"></td>
                        <td class="col-2"><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#ModalAsignacion"><i class="bi bi-pencil-fill">Editar asignación</i>
                        </td>
                    </tr>

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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modificar los tipos de tickets que recibe</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form class="d-flex justify-content-center">
                        @csrf

                        <div class=" input-group mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $categoria->id = 1 }}"
                                    id="flexCheckIndeterminate">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Bpms
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $categoria->id = 2 }}"
                                    id="flexCheckIndeterminate">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Intranet
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $categoria->id = 3 }}"
                                    id="flexCheckIndeterminate">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Cotizador
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $categoria->id = 4 }}"
                                    id="flexCheckIndeterminate">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Promo connected
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $categoria->id = 5 }}"
                                    id="flexCheckIndeterminate">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Diseño de tickets
                                </label>
                            </div>
                        </div>
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

</div>
