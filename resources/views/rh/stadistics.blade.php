@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Altas y bajas</h3>
    </div>
    <div class="card-body">
        <h5>Estadísticas de bajas</h5>
        <h6>Bajas por departamento</h6>
        <div class="row col-4">
        <canvas id="myChart" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart');
            var myChart = new Chart(ctx, {
             type: 'doughnut',
             data: {
             labels: ['Tecnología e innovación', 'Ventas'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

        <h5>Motivo de bajas</h5>
        <h6>Crecimiento laboral</h6>
        <div class="row col-4">
        <canvas id="myChart1" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart1');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Sueldo', 'Ascensos', 'Actividades desempeñadas'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

        <h6>Clima laboral</h6>
        <div class="row col-4">
        <canvas id="myChart2" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart2');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Compañeros', 'Jefe directo', 'Directivos'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

       <h6>Factores de riesgo psicosocial</h6>
        <div class="row col-4">
        <canvas id="myChart3" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart3');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Carga de trabajo', 'Falta de reconocimiento', 'Violencia laboral', 'Jornadas laborales'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

        <br>

        <h6>Demográficos</h6>
        <div class="row col-4">
        <canvas id="myChart4" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart4');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Distancia', 'Riegos físicos', 'Actividades personales', 'Actividades escolares'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

       <h6>Salud</h6>
        <div class="row col-4">
        <canvas id="myChart5" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart5');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Personal', 'Familiar'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

        <br>

        <h6>Otro</h6>
        <div class="row col-4">
        <canvas id="myChart6" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart6');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Sin respuesta'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

       <h5>Nuevos ingresos por departamento</h5>
        <div class="row col-4">
        <canvas id="myChart7" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart7');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Tecnología e innovación', 'Ventas', 'Administración', 'Recursos Humanos', 'Operaciones', 'Logística'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>

       <br>

       <h5>Bajas por departamento</h5>
        <div class="row col-4">
        <canvas id="myChart8" width="400" height="400"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
        <script>
            var ctx = document.getElementById('myChart8');
            var myChart = new Chart(ctx, {
             type: 'bar',
             data: {
             labels: ['Tecnología e innovación', 'Ventas'],
             datasets: [{
             label: 'Total:',
             data: [20, 15, 10, 5, 5, 5],
             borderWidth: 1,
             backgroundColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ],
             borderColor:[
               'rgba(255, 99, 132, 0.2)',
               'rgba(54, 162, 235, 0.2)',
               'rgba(255, 206, 86, 0.2)',
               'rgba(75, 192, 192, 0.2)',
               'rgba(153, 102, 255, 0.2)',
               'rgba(255, 159, 64, 0.2)'
                 ]
               }]
             },
             options: {
             scales: {
             y: {
             beginAtZero: true
                 }
               }
             }
           });
       </script>








    </div>
@stop
