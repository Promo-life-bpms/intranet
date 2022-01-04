@extends('layouts.app')

@section('dashboard')
    <div class="row">
        <div class="col-md-8">
            <div class="card p-3">
                <div class="row">
                    <div class="col-md-6">
                        <h4>CAPACITACIÓN: 5S´s KAIZEN 11 DE NOVIEMBRE 2021</h4>
                        <p><h6>
                             Buenos días, estimados colaboradores.
                            <br>
                            <br>
                            Esperando se encuentren muy bien y deseando que su día sea excelente, les comparto la siguiente información:
                            <br>
                            <br>
                            El día jueves 11 de noviembre se realizará una capacitación sobre “Las 5S´s Kaizen” impartida por el Ing. Cesar Arzate, del área de calidad y procesos. Esta capacitación es obligatoria para todos, por lo que les comparto las Políticas que él compartió hace unos días para Promo life y BH Trade Market.
                            <br>                        
                        </h6>
                    </p>
                       {{--  @if ($communiques)
                            @foreach ($communiques as $communique)
                                <img class="img-fluid rounded" src="{{ asset($communique->images) }}" alt="" srcset="">
                            @endforeach
                        @endif --}}
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center">
                             <h4>Ultimos comunicados</h4>
                             
                            {{--<a href="{{ route('communiques.index') }}" class="btn btn-primary btn-sm">Todos</a> --}}
                        </div>
                    
                        <ul class="list-group">
                            <img src="{{ asset('/img/kaizen.jpg')}}" alt="">

                           {{--  @if ($communiques)
                                @foreach ($communiques as $communique)
                                    <li class="list-group-item">
                                        {{ $communique->title }}
                                    </li>
                                @endforeach
                            @endif --}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>CEO Message</h4>
                <hr>
                <p><h6>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque eligendi magnam sit inventore qui
                    laudantium repellendus numquam saepe eaque sed. Inventore commodi pariatur facere quae ducimus
                    laudantium impedit veniam molestias.</h6></p>
                <span class="text-left">-David Levy</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <div class="row">
                    <h4>Cumpleaños</h4>
                    <br>
                    {{-- @foreach ($employees as $employee) --}}
                        <div class="col-md-3">
                            <div class="card" style="width: 200px; height:220px;">
                                <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                    style="width: 100%; height:150px;   object-fit: contain;" class="card-img-top" alt="imagen">
                                <div class="card-body" style="padding-top:0; padding-bottom:0">
                                    <p class="card-title text-center" style=" white-space: nowrap; margin-bottom:5px;">
                                        <p style="text-align: center"><h5>Antonio Tomas</h5> </p>
                                        {{-- {{ $employee->user->name . ' ' . $employee->user->lastname }}</p> --}}
                                    {{-- <p class="card-text text-center">{{ $employee->birthday_date }}</p> --}}
                                </div>
                            </div>
                        </div>
                    {{-- @endforeach --}}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Vacaciones</h4>
                {{-- @foreach ($employees as $employee) --}}
                        <div class="col-md-3">
                            <div class="card" style="width: 200px; height:220px;">
                                <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                    style="width: 100%; height:150px;   object-fit: contain;" class="card-img-top" alt="imagen">
                                <div class="card-body" style="padding-top:0; padding-bottom:0">
                                    <p class="card-title text-center" style=" white-space: nowrap; margin-bottom:5px;">
                                        <p style="text-align: center"><h5>Andres Martinez</h5> </p>
{{--                                         {{ $employee->user->name . ' ' . $employee->user->lastname }}</p>
 --}}                                    
                                </div>
                            </div>
                        </div>
                {{--     @endforeach --}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Aniversarios</h4>
                <br>
                <div class="row">
{{--                     @foreach ($employees as $employee)
 --}}                        <div class="col-md-3">
                            <div class="card" style="width: 200px; height:220px;">
                                <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                    style="width: 100%; height:150px;   object-fit: contain;" class="card-img-top" alt="imagen">
                                <div class="card-body" style="padding-top:0; padding-bottom:0">
                                    
                                    <p class="card-title text-center" style=" white-space: nowrap; margin-bottom:5px;">
                                        <p style="text-align: center"><h5>Diego Lopez</h5> </p>
                                       {{--  {{ $employee->user->name . ' ' . $employee->user->lastname }}</p>
                                    <p class="card-text text-center">{{ $employee->birthday_date }}</p> --}}
                                </div>
                            </div>
                        </div>
{{--                     @endforeach
 --}}                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Empleado del Mes</h4>
                <br>
            
                <div class="d-flex flex-wrap w-100 h-30 justify-content-around content-employees">
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-3">
                <h4>Calendario</h4>
                <br>
                <div  id='calendar'></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Nuevos ingresos</h4>
                <hr>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
@stop

@section('styles')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

<style>
    body {
        margin: 40px 10px;
        padding: 0;
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    }

    #calendar{
        width: 100%;
    }

    #calendar h2{
        font-size: 12px;
    }
    #calendar a{
        margin: 0 auto;
        font-size: 16px;
        color: #ffffff;
    }
    td.fc-day.fc-past {
    background-color: #ECECEC;
    }

   
</style>
@stop


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const dataHtml = document.querySelector('.content-employees')
        async function obtenerEmpleados() {
            try {
                let res = await axios.get('https://evaluacion.promolife.lat/api/empleado-del-mes');
                let data = res.data;

                dataHtml.innerHTML = `
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                        <h5 class="card-title">${data[0].name +' '+ data[0].lastname} </h5>
                                        <p class="card-text">${data[0].puesto}</p>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <p class="card-text m-0 mx-1">${data[0].star}</p>
                                            <div style="width: 30px;" class="mx-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                            <h5 class="card-title">${data[1].name +' '+ data[1].lastname} </h5>
                                            <p class="card-text">${data[1].puesto}</p>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <p class="card-text m-0 mx-1">${data[1].star}</p>
                                                <div style="width: 30px;" class="mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                                        <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                            alt="Card image cap">
                                            <h5 class="card-title">${data[2].name +' '+ data[2].lastname} </h5>
                                            <p class="card-text">${data[2].puesto}</p>
                                            <div class="d-flex justify-content-center align-items-center">
                                                <p class="card-text m-0 mx-1">${data[2].star}</p>
                                                <div style="width: 30px;" class="mx-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                </div>
                                            </div>
                                    </div>`
            } catch {
                console.log(error);
            }
        }
        obtenerEmpleados()
    </script>

<script>
    $(document).ready(function () {
       
    var SITEURL = "{{ url('/') }}";
      
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      
    let noworkingdays = @json($noworkingdays);
    let eventos =  @json($eventos);


    events = []
    noworkingdays.forEach(element => {
        events.push({
            title: element.reason,
            start: element.day,
            description:element.reason,
            rendering: 'background',
            editable: false,
            eventStartEditable:false,
        })
    });
    
    eventos.forEach(element => {
        events.push({
            title: element.title,
            start: element.start,
            description:element.description,
            editable: false,
            eventStartEditable:false,
        })
    });

    let dateActual = moment().format('YYYY-MM-DD');
    const fechasSeleccionadasEl = document.querySelector('#fechasSeleccionadas')
    var calendarEl = document.getElementById('calendar');
    var daysSelecteds = new Set();

    var calendar = $('#calendar').fullCalendar({
                        editable: true,
                        events: SITEURL + "/event",
                        displayEventTime: true,
                        allDay: true,
                        events,
                        selectable: true,
                        selectHelper: true,
                        eventMaxStack:1,
                        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
                        dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
                        dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
                        eventClick: function (event) {
                         
                            Swal.fire({
                                title: event.title,
                                html:
                                '<h4>'+event.description+'</h4>' ,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            })
                            
                        },
                        
                    });
    });
     
      
 </script>

@stop

