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
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $usuario->name }}</td>
                            <td class="col-2">
                                @if ($usuario->asignacionCategoria->count())
                                    @foreach ($usuario->asignacionCategoria as $categoria)
                                        {{ $categoria->name }}
                                    @endforeach
                                @else
                                    <p class="text-bold">No tiene categorias asignadas</p>
                                @endif

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
        aria-hidden="true" data-bs-backdrop="static">
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
                                        id="flexCheckIndeterminate" onclick="showToast(this)"
                                        wire:click='asignacion({{ $categoria->id }})' {{ $check ? 'checked' : '' }}>
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
        function showToast(checkbox) {
            if (checkbox.checked) {
                showToastMessage('green', 'Categoria Asignada');
            } else {
                showToastMessage('red', 'Se quito la categoria');
            }
        }

        function showToastMessage(color, message) {

            var toast = document.createElement('div');
            toast.style.backgroundColor = color;
            toast.style.color = 'white';
            toast.style.padding = '10px';
            toast.style.position = 'fixed';
            toast.style.top = '20px';
            toast.style.right = '20px';
            toast.style.borderRadius = '5px';
            toast.innerText = message;


            document.body.appendChild(toast);


            setTimeout(function() {
                toast.remove();
            }, 3000);
        }

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($labels), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Tickets resueltos', // Etiqueta del conjunto de datos
                        data: @json($values), // Datos obtenidos del componente
                        backgroundColor: 'rgba(0, 123, 255, 0.5)', // Color de fondo del gráfico
                        borderColor: 'rgba(0, 123, 255, 1)', // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {

                }
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('Enproceso').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($labels), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Tickets en Proceso', // Etiqueta del conjunto de datos
                        data: @json($ticketsInProcess), // Datos obtenidos del componente
                        backgroundColor: 'rgba(0, 123, 255, 0.5)', // Color de fondo del gráfico
                        borderColor: 'rgba(0, 123, 255, 1)', // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {
                    // Opciones del gráfico
                }
            });
        });
    </script>

</div>
