@extends('layouts.app')
@section('content')
    <br>
    <div class="card-header">
        <h1 class="fs-3 mx-auto">Estadísticas Tickets</h1>
    </div>
    <div class="row row-cols-3 row-cols-lg-3 g-2 g-lg-3 justify-content-center mx-auto">
        <div class="col">
            <form class="form-delete" action="{{ route('filter.estadisticas') }}" method="POST">
                @method('Post')
                @csrf
                <div class="d-flex justify-content-center">
                    <div class="form-group me-4">

                        <label><b>Fecha de inicio :</b></label>

                        <input type="date" name="startDate" class="form-control " value="{{ $startDate }}">

                    </div>
                    <div class="form-group me-4">

                        <label><b>Fecha de Termino :</b></label>

                        <input type="date" name="endDate" class="form-control  " value="{{ $endDate }}">

                    </div>
                    <div class="form-group d-flex align-items-end ">
                        <label for=""></label>
                        <button type="submit" class="btn btn-primary me-2 mt-2">Filtrar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="d-flex justify-content-center mx-auto">
            <form action="{{ route('estadisticas') }}" method="GET">
                @csrf

                <div class="form-group d-flex  mt-4">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa fa-eraser me-2" aria-hidden="true"></i>
                        Borrar filtros
                    </button>
                </div>
            </form>
        </div>
    </div>
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
                <div class="row row-cols-1 row-cols-md-2 g-2 g-md-3">
                    <div class="col">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Categoría</h6>
                        </div>
                        <canvas id="Categoria" height="200"></canvas>
                    </div>
                    <div class="col">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Mes</h6>
                        </div>
                        <canvas id="Poraño" height="200"></canvas>
                    </div>
                    <div class="col">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Agente de soporte</h6>
                        </div>
                        <canvas id="TicketsRecibidos" height="200"></canvas>
                    </div>
                    <div class="col">
                        <div class="card shadow card-total">
                            <h6 class="text-center">
                               peticiones de Usuarios</h6>
                        </div>
                        <canvas id="porUsuario" height="200"></canvas>
                    </div>
                    {{-- <div class="col ">
                        <div class="card shadow card-total">
                            <h6 class="text-center">Evaluacion</h6>
                        </div>
                        <canvas id="estrellas" height="200"></canvas>
                    </div> --}}
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

            var nombres=@json($labels);
            var datos = @json($values);

            var backgroundColors = [];
            var borderColors = [];

            for (var i = 0; i < nombres.length; i++) {
                var colorIndex = i % 5;
                backgroundColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8'][colorIndex]);
                borderColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8', '#FFADAD'][colorIndex]);
            }

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels:nombres,
                    datasets: [{
                        label: 'Categorías',
                        data: datos,
                        backgroundColor:backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {}
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('Poraño').getContext('2d');
            var labels = @json($meses);
            var data = @json($ticketsPorMes);

            var backgroundColors = [];
            var borderColors = [];

            for (var i = 0; i < labels.length; i++) {
                var colorIndex = i % 5;
                backgroundColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8'][colorIndex]);
                borderColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8', '#FFADAD'][colorIndex]);
            }

            var chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Resueltos por mes',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {}
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('TicketsRecibidos').getContext('2d');

            // var nombres= @json($usuario);
            // var ticket = @json($soporte);

            // for (var i = 0; i < nombres.length; i++) {
            //     var colorIndex = i % 5;
            //     backgroundColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8'][colorIndex]);
            //     borderColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8', '#FFADAD'][colorIndex]);
            // }

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($usuario),
                    datasets: [{
                        label: 'Agente de soporte',
                        data: @json($soporte),
                        backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD',
                            '#F5C2A8'
                        ],
                        borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8',
                            '#FFADAD'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }

                }
            });
        });

        document.addEventListener('livewire:load', function() {
            var ctx = document.getElementById('porUsuario').getContext('2d');
            var labels = @json($name);
            var data = @json($totalTicket);

            var backgroundColors = [];
            var borderColors = [];

            for (var i = 0; i  <labels.length; i++) {
                var colorIndex = i % 5;
                backgroundColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8'][colorIndex]);
                borderColors.push(['#00539C', '#EEA47F', '#EE7F7F', '#006EAD', '#F5C2A8', '#FFADAD'][colorIndex]);
            }

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($name),
                    datasets: [{
                        label: 'Usuarios',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {}
            });
        });

</script>
@endsection
