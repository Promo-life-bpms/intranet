@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Directorio de vacaciones</h3>
            {{-- <a href="{{ route('admin.vacations.create') }}" type="button" class="btn btn-success">Agregar</a> --}}
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles / Vencimineto</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($vacations as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->lastname }}</td>
                                <td>
                                    @if (count($user->vacationsAvailables) > 0)
                                        @foreach ($user->vacationsAvailables as $vacationsAvailables)
                                            <p><strong>Dias:</strong> {{ $vacationsAvailables->days_availables }}
                                                <strong>Expiracion:</strong> {{ $vacationsAvailables->expiration }}
                                            </p>
                                        @endforeach
                                    @endif
                                </td>
                                {{-- <td>{{ $user->expiration }}</td> --}}
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $vacations->links() }}
            </div>
        </div>

        <div id="RH" class="tabcontent">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rh as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($admin as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ventasBH as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ventasPL as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($importaciones as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($diseno as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sistemas as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($operaciones as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($tecnologia as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ecommerce as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cancun as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
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
                            <th style="width: 5%" scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Dias disponibles</th>
                            <th scope="col">Fecha de Vencimiento</th>
                            <th scope="col">Opciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($direccion as $vacation)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $vacation->user->name }}</td>
                                <td>{{ $vacation->user->lastname }}</td>
                                <td>{{ $vacation->days_availables }}</td>
                                <td>{{ $vacation->expiration }}</td>
                                <td class="d-flex flex-wrap">
                                    <a style="width:100px;"
                                        href="{{ route('admin.vacations.edit', ['user' => $vacation->user->id]) }}"
                                        type="button" class="btn btn-primary">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

    @stop
