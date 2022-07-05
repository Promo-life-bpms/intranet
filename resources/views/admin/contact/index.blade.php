@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Directorio telefonico y correos</h3>
            @role('systems')
                {!! Form::open(['route' => 'contacts.export']) !!}
                    {!! Form::submit('Exportar', ['class' => 'btn btn-success']) !!}
                {!! Form::close() !!}
            @endrole
        </div>
    </div>
    <div class="card-body">

        <div class="tab">
            <button class="tablinks" onclick="openDepartment(event, 'General')" id="defaultOpen">General</button>
            <button class="tablinks" onclick="openDepartment(event, 'RH')">Recursos Humamos</button>
            <button class="tablinks" onclick="openDepartment(event, 'Administracion')">Administracion</button>
            <button class="tablinks" onclick="openDepartment(event, 'VentasBH')">Ventas BH</button>
            <button class="tablinks" onclick="openDepartment(event, 'VentasPL')">Ventas PL</button>
            <button class="tablinks" onclick="openDepartment(event, 'Importaciones')">Importaciones</button>
            <button class="tablinks" onclick="openDepartment(event, 'Diseno')">Diseno</button>
            <button class="tablinks" onclick="openDepartment(event, 'Sistemas')">Sistemas</button>
            <button class="tablinks" onclick="openDepartment(event, 'Operaciones')">Operaciones</button>
            <button class="tablinks" onclick="openDepartment(event, 'Tecnologia')">Tecnologia e Innovacion</button>
            <button class="tablinks" onclick="openDepartment(event, 'Ecommerce')">E-commcerce</button>
            <button class="tablinks" onclick="openDepartment(event, 'Cancun')">Cancun</button>
            <button class="tablinks" onclick="openDepartment(event, 'Direccion')">Direccion</button>
          </div>

          <div id="General" class="tabcontent">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Departamento</th>
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
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->user->name }}</td>
                                <td>{{ $contact->user->lastname }}</td>
                                <td>{{ $contact->user->employee->position->department->name }}</td>
                                <td>{{ $contact->num_tel }}</td>
                                <td>{{ $contact->correo1 }}</td>
                                <td>{{ $contact->correo2 }}</td>
                                <td>{{ $contact->correo3 }}</td>
                                <td>{{ $contact->correo4 }}</td>
                                @role('systems')
                                    <td>
                                        <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                            type="button" class="btn btn-primary">Editar</a>
                                    </td>
                                @endrole
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            {{ $contacts->links() }}
          </div>

          <div id="RH" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                            @if ($contact->user->employee->position->department->name == 'Recursos Humanos')
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $contact->user->name }}</td>
                                    <td>{{ $contact->user->lastname }}</td>
                                    <td>{{ $contact->num_tel }}</td>
                                    <td>{{ $contact->correo1 }}</td>
                                    <td>{{ $contact->correo2 }}</td>
                                    <td>{{ $contact->correo3 }}</td>
                                    <td>{{ $contact->correo4 }}</td>
                                    @role('systems')
                                        <td>
                                            <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                                type="button" class="btn btn-primary">Editar</a>
                                        </td>
                                    @endrole
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

          </div>

          <div id="Administracion" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                            @if ($contact->user->employee->position->department->name == 'Administracion')
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->user->name }}</td>
                                <td>{{ $contact->user->lastname }}</td>
                                <td>{{ $contact->num_tel }}</td>
                                <td>{{ $contact->correo1 }}</td>
                                <td>{{ $contact->correo2 }}</td>
                                <td>{{ $contact->correo3 }}</td>
                                <td>{{ $contact->correo4 }}</td>
                                @role('systems')
                                    <td>
                                        <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                            type="button" class="btn btn-primary">Editar</a>
                                    </td>
                                @endrole
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>

          <div id="VentasBH" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Ventas BH')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div id="VentasPL" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Ventas PL')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Importaciones" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Importaciones')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Diseno" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Diseno')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Sistemas" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Sistemas')
                        <tr name="contact">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Operaciones" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Operaciones')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
         </div>


        <div id="Tecnologia" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Tecnologia e Innovacion')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Ecommerce" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'E-commerce')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Cancun" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Cancun')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

        <div id="Direccion" class="tabcontent">
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
                        @foreach ($userContact as $contact)
                        @if ($contact->user->employee->position->department->name == 'Direccion')
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $contact->user->name }}</td>
                            <td>{{ $contact->user->lastname }}</td>
                            <td>{{ $contact->num_tel }}</td>
                            <td>{{ $contact->correo1 }}</td>
                            <td>{{ $contact->correo2 }}</td>
                            <td>{{ $contact->correo3 }}</td>
                            <td>{{ $contact->correo4 }}</td>
                            @role('systems')
                                <td>
                                    <a style="width: 100%;" href="{{ route('admin.contacts.edit', ['contact' => $contact->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            @endrole
                        </tr>
                        @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>

    </div>
@stop
@section('styles')
<style>

    .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
    }

    /* Style the buttons inside the tab */
    .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 14px 16px;
      transition: 0.3s;
      font-size: 17px;
    }

    /* Change background color of buttons on hover */
    .tab button:hover {
      background-color: #ddd;
    }

    /* Create an active/current tablink class */
    .tab button.active {
      background-color: #ccc;
    }

    /* Style the tab content */
    .tabcontent {
      display: none;
      padding: 6px 12px;
      border: 1px solid #ccc;
      border-top: none;
    }
    </style>
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

    <script>
        function openDepartment(evt, Department) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(Department).style.display = "block";
        evt.currentTarget.className += " active";
        }
    </script>

    <script>
    document.getElementById("defaultOpen").click();
    </script>

    <script>
          let jquery_datatable = $("#tableTickets").DataTable()
    </script>



@stop
