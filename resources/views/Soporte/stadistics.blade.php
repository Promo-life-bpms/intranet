@extends('layouts.app')

@section('content')
    {{-- @livewire('soporte-stadistics-component') --}}


    <br>
    <div class="card-header">
        <h1 class="fs-3 mx-auto">Estadísticas Tickets</h1>
        {{-- <h2 class="fs-2 mx-auto">{{($startDate==null ? '' : $endDate)}}</h2> --}}
    </div>

    <form class="form-delete" action="{{ route('filter.stadistics') }}" method="POST">
        @method('Post')
        @csrf
        <div class="d-flex justify-content-center">
            <div class="form-group me-4">

                <label><b>Fecha de inicio :</b></label>



                <input type="date" name="startDate" class="form-control" value="{{ $startDate}}">

            </div>
            <div class="form-group me-4">

                <label><b>Fecha de Termino :</b></label>

                <input type="date" name="endDate" class="form-control" value="{{$endDate }}">

            </div>
            <div class="form-group d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filtrar</button>
            </div>
        </div>
    </form>
    <form action="{{ route('estadisticas') }}" method="GET">
        @csrf
        <div class="d-flex justify-content-center">
            <div class="form-group d-flex align-items-end">
                <button type="submit" class="btn btn-secondary">
                    <i class="fa fa-eraser me-2" aria-hidden="true"></i>
                    Borrar filtros
                </button>
            </div>

        </div>
    </form>

    <div class="card-body">
        <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3 justify-content-center">
            <div class="col">
                <div class="card shadow card-total mx-auto">
                    <div class="card-body">
                        <h5 class="card-title text-center">TOTAL TICKETS</h5>
                        <p id="total_employee" class="card-text text-center fw-bold text-dark">{{ $ticketsCreados }}</p>
                    </div>
                </div>
            </div>


            <div class="col">
                <div class="card shadow card-total mx-auto">
                    <div class="card-body">
                        <h5 class="card-title text-center">TICKETS RESUELTOS</h5>
                        <p id="total_employee" class="card-text text-center fw-bold text-success">
                            {{ $ticketsResueltos }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow card-total mx-auto">
                    <div class="card-body">
                        <h5 class="card-title text-center">TICKETS EN PROCESO</h5>
                        <p id="total_new_users" class="card-text text-center fw-bold text-danger">
                            {{ $ticketsEnProceso }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-8 col-md-8">
                <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
                    <div class="col mx-auto" wire:ignore>
                        <div class="card shadow card-total">
                            <h6 class="text-center" wire:ignore>Resueltos por categoría</h6>
                        </div>
                        <canvas id="Categoria" height="600"></canvas>

                    </div>
                    <div class="col mx-auto">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Resueltos por mes</h6>
                        </div>
                        <canvas id="Poraño" height="600"></canvas>
                    </div>

                    <div class="col mx-auto">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Recibidos por soporte</h6>
                        </div>
                        <canvas id="TicketsRecibidos" height="600"></canvas>
                    </div>
                    <div class="col mx-auto">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Enviados por usuario</h6>
                        </div>
                        <canvas id="porUsuario" height="600"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('Categoria').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($labels), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Tickets resueltos', // Etiqueta del conjunto de datos
                        data: @json($values), // Datos obtenidos del componente
                        backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ], // Color de fondo del gráfico
                        borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ],
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {}
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('Poraño').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($meses), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Resueltos por mes', // Etiqueta del conjunto de datos
                        data: @json($ticketsPorMes), // Datos obtenidos del componente
                        backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ], // Color de fondo del gráfico
                        borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8',
                            '#FFADAD'
                        ], // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {}
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('TicketsRecibidos').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($usuario), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Recibidos por soporte', // Etiqueta del conjunto de datos
                        data: @json($soporte), // Datos obtenidos del componente
                        backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ], // Color de fondo del gráfico
                        borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8',
                            '#FFADAD'
                        ], // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1 // Establece el incremento de uno en uno en el eje Y
                        }
                    }

                }
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('porUsuario').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($name), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Tickets enviados por usuarios', // Etiqueta del conjunto de datos
                        data: @json($totalTicket), // Datos obtenidos del componente
                        backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ], // Color de fondo del gráfico
                        borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8',
                            '#FFADAD'
                        ], // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {}
            });
        });
    </script>
@endsection
