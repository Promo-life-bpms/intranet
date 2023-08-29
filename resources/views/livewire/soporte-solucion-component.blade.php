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
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Status</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @if (auth()->user()->id === 127)
                        @foreach ($all_tickets as $tickets)
                            <tr>
                                <th scope="row">{{ $tickets->id }}</th>
                                <td>{{ $tickets->user->name }} {{ $tickets->user->lastname }}</td>
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
                                    @if ($tickets->status_id)
                                        @if ($tickets->status_id === 4)
                                            <button data-bs-toggle="modal"
                                                data-bs-target="#ModalAgregar{{ $tickets->id }}" type="button"
                                                class="btn btn-success btn-sm "
                                                wire:click="verTicket({{ $tickets->id }})"style="background: rgb(0, 128, 128)"><i
                                                    class="bi bi-eye"></i></button>
                                        @else
                                            <button data-bs-toggle="modal" data-bs-target="#ModalAgregar" type="button"
                                                class="btn btn-sm " wire:click="verTicket({{ $tickets->id }})"
                                                style="background: rgb(0, 128, 128)"><i class="bi bi-eye"
                                                    style="color: aliceblue"></i></button>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#Modalasignacion"
                                                wire:click="verTicket({{ $tickets->id }})">
                                                <i class="bi bi-person-fill"></i>
                                            </button>
                                            {{-- <button id="btnModalPrioridad" type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#Modalprioridad{{ $tickets->id }}"
                                            wire:click="verTicket({{ $tickets->id }})">
                                            <i class="bi bi-clock"></i>
                                        </button> --}}
                                            <button type="button" class="btn  btn-sm"
                                                onclick="finalizar({{ $tickets->id }})"
                                                style="background: rgb(241, 196, 15 )"><i class="bi bi-check-square"
                                                    style="color: aliceblue"></i></button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $all_tickets->links() }}
            </div>

        @elseif(auth()->user()->id === 31)  
            @foreach ($all_Support_tickets as $tickets)
                <tr>
                    <th scope="row">{{ $tickets->id }}</th>
                    <td>{{ $tickets->user->name }} {{ $tickets->user->lastname }}</td>
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
                        @if ($tickets->status_id)
                            @if ($tickets->status_id === 4)
                                <button data-bs-toggle="modal" data-bs-target="#ModalAgregar" type="button"
                                    class="btn btn-success btn-sm " style="background: rgb(0, 128, 128)"
                                    wire:click="verTicket({{ $tickets->id }})"><i class="bi bi-eye"></i></button>
                            @else
                                {{-- <button onclick="atender({{ $tickets->id }}, {{ $tickets->status_id }})"
                                        type="button" class="btn btn-success btn-sm "
                                        wire:click="verTicket({{ $tickets->id }})"><i
                                        class="bi bi-eye"></i></button> --}}

                                <button data-bs-toggle="modal" data-bs-target="#ModalAgregar" type="button"
                                    class="btn btn-success btn-sm " style="background: rgb(0, 128, 128)"
                                    wire:click="verTicket({{ $tickets->id }})"><i class="bi bi-eye"></i></button>

                                {{-- <button id="btnModalPrioridad" type="button" class="btn btn-info btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#Modalprioridad"
                                                wire:click="verTicket({{ $tickets->id }})">
                                                <i class="bi bi-clock"></i>                                        
                                        </button> --}}

                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#Modalasignacion" wire:click="verTicket({{ $tickets->id }})">
                                    <i class="bi bi-person-fill"></i>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm"
                                    onclick="finalizar({{ $tickets->id }})" style="background: rgb(241, 196, 15 )"><i
                                        class="bi bi-check-square" style="color: aliceblue"></i></button>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $all_Support_tickets->links() }}
            </div>
            @else
            @foreach ($solucion as $tickets)
            <tr>
                <th scope="row">{{ $tickets->id }}</th>
                <td>{{ $tickets->user->name }} {{ $tickets->user->lastname }}</td>
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
                    @if ($tickets->status_id)
                        @if ($tickets->status_id === 4)
                            <button data-bs-toggle="modal" data-bs-target="#ModalAgregar" type="button"
                                class="btn btn-success btn-sm " style="background: rgb(0, 128, 128)"
                                wire:click="verTicket({{ $tickets->id }})"><i class="bi bi-eye"></i></button>
                        @else
                            {{-- <button onclick="atender({{ $tickets->id }}, {{ $tickets->status_id }})"
                                    type="button" class="btn btn-success btn-sm "
                                    wire:click="verTicket({{ $tickets->id }})"><i
                                    class="bi bi-eye"></i></button> --}}

                            <button data-bs-toggle="modal" data-bs-target="#ModalAgregar" type="button"
                                class="btn btn-success btn-sm " style="background: rgb(0, 128, 128)"
                                wire:click="verTicket({{ $tickets->id }})"><i class="bi bi-eye"></i></button>

                            {{-- <button id="btnModalPrioridad" type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#Modalprioridad"
                                            wire:click="verTicket({{ $tickets->id }})">
                                            <i class="bi bi-clock"></i>                                        
                                    </button> --}}

                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#Modalasignacion" wire:click="verTicket({{ $tickets->id }})">
                                <i class="bi bi-person-fill"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm"
                                onclick="finalizar({{ $tickets->id }})" style="background: rgb(241, 196, 15 )"><i
                                    class="bi bi-check-square" style="color: aliceblue"></i></button>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $solucion->links() }}
        </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="ModalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable  modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Ticket</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="historial-tab" data-bs-toggle="tab"
                                data-bs-target="#historial" type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Historial</button>
                        </li>
                        <li class="nav-item" role="presentation" wire:ignore>
                            <button class="nav-link" id="mensaje-tab" data-bs-toggle="tab" data-bs-target="#mensaje"
                                type="button" role="tab" aria-controls="historial"
                                aria-selected="false">Mensajes</button>
                        </li>
                        @if ($estrellas && $estrellas->score)
                            <li class="nav-item" role="presentation" wire:ignore>
                                <button class="nav-link" id="calificacion-tab" data-bs-toggle="tab"
                                    data-bs-target="#calificacion" type="button" role="tab"
                                    aria-controls="historial" aria-selected="false">Evaluación Servicio</button>
                            </li>
                        @endif
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab" wire:ignore.self>
                            <form method="POST">
                                @csrf

                                <p><span class="fw-bold ">Creado :</span> <span class="">
                                        {{ $ticket_creado }}</span>
                                        @if ($status_id === 4)
                                        @else
                                            <button id="btnModalPrioridad" type="button" class="btn btn-info btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#Modalprioridad{{ $ticket_id }}  "
                                                wire:click="verTicket({{ $ticket_id }})"> <i class="bi bi-clock"></i>
                                            </button>
                                        @endif
                                </p>

                                <p id="demo"></p>

                                <p><span class="fw-bold ">Usuario :</span> <span class=""> {{ $nombre }}
                                        {{ $apellido }}</span>                                 
                                </p>


                                <p><span class="fw-bold ">Departamento :</span> <span
                                        class="">{{ $departamento }}</span>
                                </p>

                                <p><span class="fw-bold ">Categoría :</span> <span
                                        class="">{{ $categoria }}</span></p>



                                @if ($prioridad == '00:00:00')
                                @elseif ($prioridad == '01:00:00' || $prioridad == '03:00:00' || $prioridad == '05:00:00')
                                    <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                class="badge bg-info text-dark">{{ $prioridad }}</span></span></p>
                                @endif

                                @if ($especial)
                                    @if ($especial == '24:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">1 día</span></span></p>
                                    @elseif ($especial == '48:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">2 días</span></span></p>
                                    @elseif ($especial == '72:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">3 días</span></span></p>
                                    @elseif ($especial == '96:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">4 días</span></span></p>
                                    @elseif ($especial == '120:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">5 días</span></span></p>
                                    @elseif ($especial == '01:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">1 hora</span></span></p>
                                    @elseif ($especial == '02:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">2 horas</span></span></p>
                                    @elseif ($especial == '03:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">3 horas</span></span></p>
                                    @elseif ($especial == '04:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">4 horas</span></span></p>
                                    @elseif ($especial == '05:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">4 horas</span></span></p>
                                    @elseif ($especial == '06:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">6 horas</span></span></p>
                                    @elseif ($especial == '07:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">7 horas</span></span></p>
                                    @elseif ($especial == '08:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">8 horas</span></span></p>
                                    @elseif ($especial == '09:00:00')
                                        <p><span class="fw-bold">Tiempo estimado a ser resuelto: <span
                                                    class="badge bg-info text-dark">9 horas</span></span></p>
                                    @endif
                                @endif

                                <p><span class="fw-bold  ">Descripción:</span></p>


                                <div class="container">
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
                                            <div class="spinner-border text-dark" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @endif

                        </div>

                        <div class="tab-pane fade" id="calificacion" role="tabpanel" wire:ignore.self>
                            <div class="d-flex justify-content-center">
                                <style>
                                    #form {
                                        width: 250px;
                                        margin: 0 auto;
                                        height: 50px;
                                    }

                                    #form label {
                                        font-size: 200px;
                                        /* Tamaño de fuente ajustado */
                                        margin-right: 10px;
                                        /* Margen derecho para separar las estrellas */
                                    }

                                    input[type="radio"] {
                                        display: none;

                                    }

                                    label {
                                        color: grey;
                                    }

                                    .clasificacion {
                                        direction: rtl;
                                        unicode-bidi: bidi-override;
                                    }

                                    label:hover,
                                    label:hover~label {
                                        color: orange;
                                    }

                                    input[type="radio"]:checked~label {
                                        color: orange;
                                    }
                                </style>
                                <div class="d-flex justify-content-center">
                                    @if ($estrellas && $estrellas->score)
                                        <p class="clasificacion">

                                            @if ($estrellas->score == 5)
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                            @elseif ($estrellas->score == 4)
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                            @elseif ($estrellas->score == 3)
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                            @elseif ($estrellas->score == 2)
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                            @else
                                                <label for="radio1" class="fs-1 text-warning">★</label>
                                            @endif

                                        </p>
                                    @endif
                                </div>

                            </div>
                            @if ($comments)
                                <div class="d-flex justify-content-center">
                                    <span class="fw-bold">Comentarios :</span>
                                    <p>
                                        {{ $comments->comments }}
                                    </p>
                                </div>
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
                                    @elseif ($cambio->type == 'Reasignacion')
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
                                                            <h4 class="card-title text-green">Ticket reasignado a:</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'Tiempo')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-clock"></i></span>
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
                                                            <h4 class="card-title text-green">Tiempo de solución
                                                                asignado</h4>
                                                            <p class="card-text text-muted">{!! $cambio->data !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($cambio->type == 'Encuesta')
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-auto text-center  flex-column  d-none  d-sm-flex">
                                                    <div class="row h-50">
                                                        <div class="col">&nbsp;</div>
                                                        <div class="col ">&nbsp;</div>
                                                    </div>
                                                    <h5 class="m-2">
                                                        <span class=" rounded-circle bg-light "><i
                                                                class="bi bi-card-checklist"></i></span>
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
                                                            <h4 class="card-title text-green">Evaluación soporte
                                                                realizada</h4>

                                                            {{-- <p class="card-text text-muted">{!! $cambio->data !!}</p> --}}
                                                            <div class="d-flex justify-content-center">
                                                                <style>
                                                                    #form {
                                                                        width: 250px;
                                                                        margin: 0 auto;
                                                                        height: 50px;
                                                                    }

                                                                    #form label {
                                                                        font-size: 200px;
                                                                        /* Tamaño de fuente ajustado */
                                                                        margin-right: 10px;
                                                                        /* Margen derecho para separar las estrellas */
                                                                    }

                                                                    input[type="radio"] {
                                                                        display: none;

                                                                    }

                                                                    label {
                                                                        color: grey;
                                                                    }

                                                                    .clasificacion {
                                                                        direction: rtl;
                                                                        unicode-bidi: bidi-override;
                                                                    }

                                                                    label:hover,
                                                                    label:hover~label {
                                                                        color: orange;
                                                                    }

                                                                    input[type="radio"]:checked~label {
                                                                        color: orange;
                                                                    }
                                                                </style>
                                                                <div class="d-flex justify-content-center">
                                                                    <p class="clasificacion">
                                                                        <input id="radio1" disabled disabled
                                                                            type="radio" name="estrellas"
                                                                            class="form-check-input me-1 fs-1"
                                                                            id="estrella_5"
                                                                            @if ($cambio->data == 5) checked @endif>
                                                                        <label for="radio1" class="fs-1">★</label>
                                                                        <input id="radio2" disabled type="radio"
                                                                            name="estrellas"
                                                                            class="form-check-input me-1 fs-1"
                                                                            id="estrella_4"
                                                                            @if ($cambio->data == 4) checked @endif>
                                                                        <label for="radio2" class="fs-1">★</label>
                                                                        <input id="radio3" disabled type="radio"
                                                                            name="estrellas"
                                                                            class="form-check-input me-1 fs-1"
                                                                            id="estrella_3"
                                                                            @if ($cambio->data == 3) checked @endif>
                                                                        <label for="radio3" class="fs-1">★</label>
                                                                        <input id="radio4" disabled type="radio"
                                                                            name="estrellas"
                                                                            class="form-check-input me-1 fs-1"
                                                                            id="estrella_2"
                                                                            @if ($cambio->data == 2) checked @endif>
                                                                        <label for="radio4" class="fs-1">★</label>
                                                                        <input id="radio5" disabled type="radio"
                                                                            name="estrellas"
                                                                            class="form-check-input me-1 fs-1"
                                                                            id="estrella_1"
                                                                            @if ($cambio->data == 1) checked @endif>
                                                                        <label for="radio5" class="fs-1">★</label>
                                                                    </p>
                                                                </div>

                                                            </div>
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
                            wire:ignore.self>
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
                                            <div class="spinner-border text-dark" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="Modalasignacion" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Reasignar Ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect01">Usuarios</label>
                        <select wire:model="usuario_reasignacion" name="usuario_reasignacion" class="form-select"
                            id="inputGroupSelect01">
                            <option value="" selected>Seleccionar</option>
                            @foreach ($users as $user)
                                @if ($user->id !== auth()->user()->id)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach

                        </select>

                    </div>
                    @error('usuario_reasignacion')
                        <span>
                            <font color="red"> *Selecciona un usuario* </font>
                        </span>
                        <br>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                    @if (isset($tickets->id))
                        <button type="button"
                            class="btn btn-primary"wire:click="reasignar({{ $tickets->id }})">Reasignar</button>

                        <div wire:loading.flex wire:target="reasignar">
                            <div class="spinner-border text-dark" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="Modalprioridad{{ $ticket_id }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Asignar tiempo de solución</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if ($especial > '00:00:00')
                    @else
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Prioridad</label>
                            <select wire:model="tiempo" name="tiempo" class="form-select"
                                onchange="showTimeInput($this)">
                                <option value="" selected>Seleccionar</option>
                                @foreach ($priority as $prioritys)
                                    <option value="{{ $prioritys->id }}">{{ $prioritys->priority }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('tiempo')
                            <span>
                                <font color="red"> *Selecciona la prioridad* </font>
                            </span>
                            <br>
                        @enderror
                    @endif

                    @if ($prioridadID == 2 || $prioridadID == 3 || $prioridadID == 4)
                    @else
                        <button class="btn btn-info" onclick="hide()">Especial</button>


                        <div id="timeInput" class="d-none">
                            {{-- <label class="input-group-text" for="inputGroupSelect01">Tiempo</label> --}}
                            {{-- <input wire:model="tiempo" type="time" name="tiempo"> --}}
                            <hr>
                            <div class="input-group mb-3">
                                <label class="input-group-text" for="inputGroupSelect02">Horas</label>
                                <select wire:model="time_special" name="time_special" class="form-select"
                                    id="inputGroupSelect02">
                                    <option value="" selected>Seleccionar...</option>
                                    <option value="01:00">01:00</option>
                                    <option value="02:00">02:00</option>
                                    <option value="03:00">03:00</option>
                                    <option value="04:00">04:00</option>
                                    <option value="5:00">05:00</option>
                                    <option value="6:00">06:00</option>
                                    <option value="7:00">07:00</option>
                                    <option value="8:00">08:00</option>
                                    <option value="9:00">09:00</option>
                                    <option value="24:00">1 día</option>
                                    <option value="48:00">2 días</option>
                                    <option value="72:00">3 días</option>
                                    <option value="96:00">4 días</option>
                                    <option value="120:00">5 días</option>
                                </select>
                                @if (isset($tickets->id))
                                    <button type="button"
                                        class="btn btn-success rounded-3"wire:click="special({{ $tickets->id }})">Asignar</button>
                                    <div wire:loading wire:target="special">
                                        <div class="spinner-border text-dark" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('time_special')
                                <span>
                                    <font color="red"> *Selecciona una hora* </font>
                                </span>
                            @enderror
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if (isset($tickets->id))
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        @if ($especial)
                        @else
                            <button type="button"
                                class="btn btn-primary"wire:click="time({{ $tickets->id }})">Asignar</button>
                            <div wire:loading.flex wire:target="time">
                                <div class="spinner-border text-dark" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        @endif

                    @endif
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

        window.addEventListener('mensaje_soporte', () => {
            Swal.fire({
                icon: 'success',
                title: 'mensaje enviado correctamente',
                showConfirmButton: false,
                timer: 1500
            })
            ckeEditorMensaje.setData("");
        });

        window.addEventListener('reasignacion', () => {
            Swal.fire({
                icon: 'success',
                title: 'ticket reasignado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#Modalasignacion').modal('hide')
        });

        window.addEventListener('Tiempo', () => {
            Swal.fire({
                icon: 'success',
                title: 'Tiempo asignado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#Modalprioridad').modal('hide');

        });

        window.addEventListener('special', () => {
            Swal.fire({
                icon: 'success',
                title: 'Tiempo asignado correctamente',
                showConfirmButton: false,
                timer: 1500
            })

            $('#Modalprioridad').modal('hide');

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
                        ckeEditorMensaje.model.document.on('change', () => {
                            const content = ckeEditorMensaje.getData();
                            @this.mensajes = content
                            console.log(content);
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


        function showTimeInput(selectElement) {
            var timeInput = document.getElementById('timeInput');

            if (selectElement.value === 'Especial') {
                timeInput.style.display = 'block';
            } else {
                timeInput.style.display = 'none';
            }
        }

        function hide() {
            //JQERY
            $('#timeInput').toggleClass('d-none');
        }

        

    </script>
 
</div>
