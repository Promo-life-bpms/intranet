<div>
    <h1 class="fs-3">Tickets Soporte</h1>

    <div class="row">
        <div class="col-sm-8 col-md-8">
            <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
                <div class="col">
                    <h6>Resueltos por categoria</h6>
                    <canvas id="myChart" height="700"></canvas>
                </div>

                <div class="col">
                    <h6>Por mes</h6>
                    <canvas id="Enproceso" height="700"></canvas>
                </div>
            </div>
        </div>

    </div>

    <script>
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
                    // Opciones del gráfico
                }
            });
        });


        document.addEventListener('livewire:load', function () {
            var ctx = document.getElementById('Enproceso').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar', // Cambia el tipo de gráfico según tus necesidades
                data: {
                    labels: @json($meses), // Etiquetas obtenidas del componente
                    datasets: [{
                        label: 'Resueltos por mes', // Etiqueta del conjunto de datos
                        data: @json($ticketsPorMes), // Datos obtenidos del componente
                        backgroundColor:   'rgba(0, 123, 255, 0.5)', // Color de fondo del gráfico
                        borderColor: 'rgba(0, 123, 255, 1)', // Color del borde del gráfico
                        borderWidth: 1 // Ancho del borde del gráfico
                    }]
                },
                options: {
                    // Opciones del gráfico
                }
            });
        });

        // document.addEventListener('livewire:load', function () {
        //     var ctx = document.getElementById('Enproceso').getContext('2d');
        //     var chart = new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: @json($labels),
        //             datasets: [{
        //                 label: 'Resueltos categoria'
        //                 backgroundColor: 'rgba(0, 123, 255, 0.5)',
        //                 borderColor: 'rgba(0, 123, 255, 1)',
        //                 borderWidth: 1
        //             }]
        //         },
        //         options: {
        //             // Opciones del gráfico
        //         }x
        //     });
        // });
    </script>

</div>
