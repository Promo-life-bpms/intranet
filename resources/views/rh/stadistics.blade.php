@extends('layouts.app')

@section('content')


<?php
$subheader = 'fs-6';
$subheader2 = 'fs-5';
$subcols = 'col-4 p-4';
$subtext = 'fs-6  m-0';
define('SUB_TEXT','fs-6 my-0 mx-2');
define('SUB_COLU','col-4 p-2');
?>

<div class="card-header p-0">
    <h1 class="fs-3">Altas y bajas</h1>
    <div class="card-body p-0">
        <div class ="d-flex flex-row-reverse">    
            <form class="form-delete"
                    action="/rh/drop-user/"
                    method="GET">
                     @csrf
                    <button style="" type="submit" class="btn btn-info">Generar baja</button>
            </form>
        <div style="margin-left:10px"></div>
    
            <form class="form-delete"
                    action="/rh/postulants/"
                    method="GET">
                     @csrf
                    <button style="" type="submit" class="btn btn-danger">Generar Alta</button>
            </form>
        <div style="margin-left:10px"></div>

            <form class="form-delete"
                    action=""
                    method="DELETE">
                     @csrf
                    <button style="" type="submit" class="btn btn-success">Exportar</button>
            </form> 
        </div>
    </div>
</div>

                <div class="form-group">
                    {!! Form::label('Periodo: ') !!}
                    {!! Form::date(' ') !!}
                    @error('')
                    @enderror
                    {!! Form::label('a') !!}
                    {!! Form::date(' ') !!}
                    @error('')
                    @enderror
                    <button style="" type="submit" class="btn btn-primary btn-sm">Filtrar</button>
                
                </div>

                


<div class="d-flex flex-row justify-content-end align-items-center mb-5">
        <h3 class="{{ SUB_TEXT }}">Empresa:</h3>   
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width:190px">
            Selecciona la empresa
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"></a>Promo Life</li>
                <li><a class="dropdown-item" href="#"></a>BH Trade Market</li>
                <li><a class="dropdown-item" href="#"></a>Promo Zale</li>
                <li><a class="dropdown-item" href="#"></a>Trade Market 57</li>
                <li><a class="dropdown-item" href="#"></a>Unipromtex</li></ul>
        </div>
            
</div>


<?php
    $style='display:flex; flex-direction:column; justify-content:space-between; box-shadow: 0px 1px 10px rgba(0, 0, 0,0.2); margin:10px; padding:15px; height:20px;';
?>

<div class="row row-cols-1 row-cols-md-0 g-0" style =<?php echo $style ?>>
    <article id="otros" class="sombra" style=<?php echo $style ?>>
        <div class="col">
            <div class="card h-100">
                <div class="card-body" >
                    <h5 class="card-title">TOTAL EMPLEADOS</h5>
                    <p class="card-text" style="color:#000080">{{$totalEmpleados->total}}</p>
                </div>
            </div>
        </div>
    </article>

    <article id="otros" class="sombra" style=<?php echo $style ?>>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">NUEVOS INGRESOS</h5>
                    <p class="card-text" style="color:#308446">{{$nuevosingresos}}</p>
                </div>
            </div>
        </div>
    </article>

    <article id="otros" class="sombra" style=<?php echo $style ?>>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">BAJAS</h5>
                    <p class="card-text" style="color:#FF0000">{{$bajas}}</p>
                </div>
            </div>
        </div>
    </article>
    
    <article id="otros" class="sombra" style=<?php echo $style ?>>
        <div class="col">
            <div class="card h-100">
                <div class="card-body">
                <h5 class="card-title">VACANTES</h5>
                    <p class="card-text">2</p>
                </div>
            </div>
        </div>
    </article>
</div>



<div class="d-flex flex-row my-5">
    <div class="d-flex flex-column col-4">
        <h2 class=<?php echo $subheader2 ?>>Estadísticas de bajas</h2>                      
            <div id="bajas">
                <h3 class=<?php echo $subheader ?>>Bajas por departamento</h3>
                <canvas id="myChart1" width="300" height="300"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                <script>
                    var ctx = document.getElementById('myChart1');
                    var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                    labels: ['Tecnología e innovación', 'Ventas'],
                    datasets: [{
                    label: 'Total:',
                    data: [20, 15],
                    borderWidth: 1,
                    backgroundColor:[
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
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
    </div>          
    <div class="d-flex flex-column">                
        <h2 class=<?php echo $subheader2 ?>>Motivo de bajas</h2>
            <div class="d-flex flex-row flex-wrap overflow-x-scroll">
                <div id="crecimiento" class="{{ SUB_COLU }}">  
                    <h3 class=<?php echo $subheader ?>>Crecimiento laboral</h3>
                    <canvas id="myChart2" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart2');
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
                </div>  
                <div id="clima" class="{{ SUB_COLU }}">
                    <h3 class=<?php echo $subheader ?>>Clima laboral</h3>
                    <canvas id="myChart3" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart3');
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
                </div>  
                <div id="factores" class="{{ SUB_COLU }}">  
                    <h3 class=<?php echo $subheader ?>>Factores de riesgo psicosocial</h3>
                    <canvas id="myChart4" width="300" height="300" style="height: 300px"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart4');
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
                </div>
                <div id="demograficos" class="{{ SUB_COLU }}">  
                    <h3 class=<?php echo $subheader ?>>Demográficos</h3>
                    <canvas id="myChart5" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart5');
                            var myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                            labels: ['Distancia', 'Riesgos físicos', 'Actividades personales', 'Actividades escolares'],
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
                <div id="salud" class="{{ SUB_COLU }}">  
                    <h3 class=<?php echo $subheader ?>>Salud</h3>
                    <canvas id="myChart6" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart6');
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
                </div>
                <div id="Otro" class="{{ SUB_COLU }}">  
                    <h3 class=<?php echo $subheader ?>>Otro</h3>
                    <canvas id="myChart7" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart7');
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
                </div>   
            </div>            
    </div>
</div>

<div class="d-flex flex-row d-flex justify-content-around">
    <div class="d-flex flex-column col-4">
        <h2 class=<?php echo $subheader2 ?>>Nuevos ingresos por departamento</h2>                      
            <div id="nuevos ingresos">
                <canvas id="myChart8" width="300" height="300"></canvas>
                <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                <script>
                    var ctx = document.getElementById('myChart8');
                    var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                    labels: ['Tecnología e innovación', 'Ventas', 'Administración', 'Recursos Humanos', 'Operaciones', 'Logistica'],
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
</div>

<div class="d-flex flex-row d-flex justify-content-around">                
        <h2 class=<?php echo $subheader2 ?>>Bajas por departamento</h2>
            <div class="d-flex flex-row flex-wrap overflow-x-scroll">
                <div id="bajas">  
                    <canvas id="myChart9" width="300" height="300"></canvas>   
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
                    <script>
                            var ctx = document.getElementById('myChart9');
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
            </div>
</div>
@stop

@section ('styles')
    <style>
    
    .card {
        border-radius: 7px;  
        overflow: hidden;
        background: #fff;          
        cursor:default;
        transition: all 400 ms ease;
    }
    .sombra{
        height: 20vh;
        width: 35vh;
        margin:13px;
        border-radius: 6px;
        box-shadow: 5px 5px 20px rgba(0, 0, 0,0.4);
        cursor: default;
        transform: translateY(-3%);
    }
    #otros{
        display: contents;
    }
    .card .card-text{
        padding:10px;
        text-align:center;
        font-size: 50px;
    }
     h5{
        text-align:center;
     }

     p{
        text-align:center;
     }     
    </style>

    @endsection



