@extends('layouts.app')

@section('content')


<?php
$subheader = 'fs-6';
$subheader2 = 'fs-5';
$subcols = 'col-4 p-4';
$subtext = 'fs-6  m-0';
$data = 'hola1';
define('SUB_TEXT', 'fs-6 my-0 mx-2');
define('SUB_COLU', 'col-4 p-2');
?>

<div class="card-header p-0">
    <h1 class="fs-3">Altas y bajas</h1>
    <div class="card-body p-0">
        <div class="d-flex flex-row-reverse">
            <form class="form-delete" action="/rh/drop-user" method="GET">
                @csrf
                <button type="submit" class="btn btn-danger">Generar baja</button>
            </form>
            <div style="margin-left:10px"></div>

            <form class="form-delete" action="/rh/postulants" method="GET">
                @csrf
                <button type="submit" class="btn btn-success">Generar Alta</button>
            </form>
            <div style="margin-left:10px"></div>
            <form class="form-delete" action="/rh/stadistics" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary">Borrar filtros</button>
            </form>

            <br>
        </div>
    </div>

    <form class="form-delete" action="{{ route('rh.filterstadistics') }}" method="POST">
        @csrf
        @method('post')

        <div class="d-flex justify-content-between">

            <div class="d-flex justify-content-start">
                <div class="form-group me-4">
                    <label><b>Fecha de inicio: </b></label>
                    <input type="date" id="start" name="start" value="{{$start}}" class="form-control">
                </div>

                <div class="form-group me-4">
                    <label><b> Fecha de termino: </b></label>
                    <input type="date" name="end" id="end" value="{{$end}}" class="form-control">
                </div>

                <div class="form-group">
                    <label><b></b></label> <br>
                    <input type="submit" class="btn btn-primary" value="Filtrar" />
                </div>
                
               
            </div>

            <div class="d-flex align-items-center">
                <select class="form-control" name="select" id="company_name">
                    <option value="todas" selected>Todas las empresas</option>
                    <option value="promolife">Promo Life</option>
                    <option value="bhtrademarket">BH Trade Market</option>
                    <option value="promozale">Promo Zale</option>
                    <option value="trademarket57">Trade Market 57</option>
                    <option value="unipromtex">Unipromtex</option>
                </select>
            </div>

        </div>
    </form>

   
    
    <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3" >
        
        <div class="col">
            <div class="card card-total">
                <div class="card-body">
                    <h5 class="card-title">TOTAL EMPLEADOS</h5>
                    <p id="total_employee" class="card-text" style="color:#000080">{{$totalEmpleados->total}}</p>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card card-total ">
                <div class="card-body">
                    <h5 class="card-title">NUEVOS INGRESOS</h5>
                    <p id="total_new_users" class="card-text" style="color:#308446">{{$nuevosingresos->total}} </p>
                </div>
            </div>
        </div>

  
        <div class="col">
            <div class="card card-total">
                <div class="card-body">
                    <h5 class="card-title">BAJAS</h5>
                    <p id="total_downs" class="card-text" style="color:#FF0000">{{$bajas->total}} </p>
                </div>
            </div>
        </div>        
    </div>

    <!--   Estadisticas -->
    <div class="row">
        <div class="col-sm-4 col-md-4">
            <div class="d-flex flex-column">
                <h4>Estadísticas de bajas</h4>
                <div id="bajas">
                    <h6>Bajas por departamento</h6>
                    <canvas id="circle_chart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-sm-8 col-md-8">
            <h4>Motivo de bajas</h4>
            <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-3">
                <div class="col">
                    <h6>Crecimiento laboral</h6>
                    <canvas id="laboral_grown" height="300"></canvas>
                </div>

                <div class="col">
                    <h6>Clima laboral</h6>
                    <canvas id="laboral_climate" width="300" height="300"></canvas>
                </div>

                <div class="col">
                    <h3 class=<?php echo $subheader ?>>Factores de riesgo psicosocial</h3>
                    <canvas id="risk_factors" width="300" height="300" style="height: 300px"></canvas>
                </div>

                <div class="col">
                    <h6>Demográficos</h6>
                    <canvas id="demographics" width="300" height="300"></canvas>
                </div>

                <div class="col">
                    <h6>Salud</h6>
                    <canvas id="health" width="300" height="300"></canvas>
                </div>

                <div class="col">
                    <h6>Otro</h6>
                    <canvas id="other" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

@stop

@section ('styles')
    <style>
    
        .card-total {
            background-color: #FDFBFB;
            border: 1px solid;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
            border-radius: 10px;
            padding: 20px 0 20px 0;
        }

        .card .card-body h5{
            text-align: center;
            font-size: 28px;
        }

        .card .card-body p{
            text-align: center;
            font-size: 36px;
        }
      
    </style>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>

    <script>
    
        var circle_chart = document.getElementById('circle_chart');
        var laboral_grown = document.getElementById('laboral_grown');
        var laboral_climate = document.getElementById('laboral_climate');
        var risk_factors = document.getElementById('risk_factors');
        var demographics = document.getElementById('demographics');
        var health = document.getElementById('health');
        var other = document.getElementById('other');
        var new_users = document.getElementById('new_users');
        var department_down =  document.getElementById('department_down');
        
        var department_chart = {!! json_encode($motive) !!};

        var department_list_name = [];
        var department_list_total = [];
        var laboral_grown_list = [];
        var laboral_climate_list = [];
        var risk_factors_list = [];
        var demographics_list = [];
        var health_list = [];
        var other_motive_list = [];

        department_chart.forEach(element => department_list_name.push(element.department));
        department_chart.forEach(element => department_list_total.push(element.total));
        
        department_chart.forEach(element => laboral_grown_list.push(
            {
                label: element.department,
                data:[element.growth_salary, element.growth_promotion, element.growth_activity],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        ); 

        department_chart.forEach(element => laboral_climate_list.push(
            {
                label: element.department,
                data:[element.climate_partnet, element.climate_manager, element.climate_boss],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        );

        department_chart.forEach(element => risk_factors_list.push(
            {
                label: element.department,
                data:[element.psicosocial_workloads, element.psicosocial_appreciation, element.psicosocial_violence, element.psicosocial_workday],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        );

        department_chart.forEach(element => demographics_list.push(
            {
                label: element.department,
                data:[element.demographics_distance, element.demographics_physical, element.demographics_personal, element.demographics_school],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        );

        department_chart.forEach(element => health_list.push(
            {
                label: element.department,
                data:[element.health_personal, element.health_familiar],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        );

        department_chart.forEach(element => other_motive_list.push(
            {
                label: element.department,
                data:[element.other_motive],
                borderWidth: 1,
                backgroundColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
                borderColor: ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',],
            })
        );

        //Estilos de Tablas 
        var settings_chart_colors = ['#00539C', '#EEA47F', '#EE7F7F', '#006EAD','#F5C2A8','#FFADAD',]
        var settings_chart_options = { scales: { xAxes: [{ ticks: { fontSize: 5 }  }] } }
 
        //Bajas por departamento
        new Chart(circle_chart, {
            type: 'doughnut',
            data: {
            labels: department_list_name,
                    datasets: [{
                        label: 'Total:',
                        data: department_list_total,
                        borderWidth: 1,
                        backgroundColor:settings_chart_colors ,
                        borderColor: settings_chart_colors,
                }]
            },
        });

        //Crecimiento laboral   
        new Chart(laboral_grown, {
            type: 'bar',
            data: {
                labels: ['Sueldo', 'Ascensos', 'Actividades desempeñadas'],
                datasets:laboral_grown_list
                },
            options: settings_chart_options
        });
 
        //Clima laboral
        new Chart(laboral_climate, {
            type: 'bar',
            data: {
                labels: ['Compañeros', 'Jefe directo', 'Directivos'],
                datasets:laboral_climate_list
                },
            options: settings_chart_options
        });

        //Factores de riesgo psicosocial              
        new Chart(risk_factors, {
            type: 'bar',
            data: {
                labels: ['Carga de trabajo', 'Falta de reconocimiento', 'Violencia laboral', 'Jornadas laborales'],
                datasets: risk_factors_list
                },
            options:settings_chart_options
        });

        //Demograficos          
        new Chart(demographics, {
            type: 'bar',
            data: {
                labels: ['Distancia', 'Riesgos físicos', 'Actividades personales', 'Actividades escolares'],
                datasets: demographics_list
            },
            options:settings_chart_options
        });

        //Salud
        new Chart(health, {
            type: 'bar',
            data: {
                labels: ['Personal', 'Familiar'],
                datasets: health_list
            },
            options: settings_chart_options
        });

        //Otro
        new Chart(other, {
            type: 'bar',
            data: {
                labels: ['Otro'],
                datasets: other_motive_list
            },
            options: settings_chart_options
        });
               
        //Nuevos Ingresos por departamento     
        new Chart(new_users, {
            type: 'bar',
            data: {
                labels: ['Tecnología e innovación', 'Ventas', 'Administración', 'Recursos Humanos', 'Operaciones', 'Logistica'],
                datasets: [{
                    label: 'Total:',
                    data: [20, 15, 10, 5, 5, 5],
                    borderWidth: 1,
                    backgroundColor: settings_chart_colors,
                    borderColor: settings_chart_colors
                }]
            },
            options: settings_chart_options
        });
                   
        //Bajas por departamento         
        new Chart(department_down, {
            type: 'bar',
            data: {
                labels: ['Tecnología e innovación', 'Ventas'],
                datasets: [{
                    label: 'Total:',
                    data: [20, 15, 10, 5, 5, 5],
                    borderWidth: 1,
                    backgroundColor: settings_chart_colors,
                    borderColor:settings_chart_colors
                }]
            },
             options: settings_chart_options
        });       
            
    </script>
    
    <script>
        const postulantStatus = document.getElementById("company_name");
        postulantStatus.addEventListener("change", statusChange);

        function statusChange(event) {
            const currentValue = event.target.value;
            console.log(currentValue);

            var employees = {!!json_encode($totalEmpleados) !!};
            var new_users = {!!json_encode($nuevosingresos) !!};
            var downs = {!!json_encode($bajas) !!};

            if (currentValue == 'promolife') {
                console.log("PROMOLIFE");
                console.log(employees.promolife);
                document.getElementById('total_employee').innerHTML = employees.promolife;
                document.getElementById('total_new_users').innerHTML = new_users.promolife;
                document.getElementById('total_downs').innerHTML = downs.promolife;
            }

            if (currentValue == 'bhtrademarket') {
                console.log("BHTRADEMARKET");
                console.log(employees.bh_trade_market);
                document.getElementById('total_employee').innerHTML = employees.bh_trade_market;
                document.getElementById('total_new_users').innerHTML = new_users.bh_trade_market;
                document.getElementById('total_downs').innerHTML = downs.bh_trade_market;
            }

            if (currentValue == 'promozale') {
                document.getElementById('total_employee').innerHTML = employees.promo_zale;
                document.getElementById('total_new_users').innerHTML = new_users.promo_zale;
                document.getElementById('total_downs').innerHTML = downs.promo_zale;
            }

            if (currentValue == 'trademarket57') {
                document.getElementById('total_employee').innerHTML = employees.trade_market57;
                document.getElementById('total_new_users').innerHTML = new_users.trade_market57;
                document.getElementById('total_downs').innerHTML = downs.trade_market57;
            }

            if (currentValue == 'todas') {
                document.getElementById('total_employee').innerHTML = employees.total;
                document.getElementById('total_new_users').innerHTML = new_users.total;
                document.getElementById('total_downs').innerHTML = downs.total;
            }
        }
       
    </script>
@stop