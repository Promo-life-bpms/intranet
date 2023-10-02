@extends('layouts.app')

@section('dashboard')
    <div class="row">
        <!-- Seccion principal -->
        <div class="col-md-8">
            <!-- Crear publicacion -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="">
                            @if (auth()->user()->image == null)
                                <a style="color: inherit;" href="{{ route('profile.index') }}">
                                    <p
                                        class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                        <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                    </p>
                                </a>
                            @else
                                <a href="{{ route('profile.index') }}">
                                    <img style="width: 55px; height:55px; object-fit: cover;" class="rounded-circle"
                                        src="{{ asset(auth()->user()->image) }}">
                                </a>
                            @endif
                        </div>
                        <form action="{{ route('publications.store') }}" class="form-publicar ml-2 flex-grow-1"
                            id="formCreate" method="post">
                            {!! csrf_field() !!}
                            @method('PATCH')
                            @if (session('errorData'))
                                <div class="text-danger" id="messageError">
                                    {{ session('errorData') }}
                                </div>
                            @endif
                            <textarea id="exampleFormControlTextarea1" class="form-control" name="content_publication" style="height: 55px"
                                placeholder="¿Qué estás pensando?"></textarea>
                            <div class="collapse" id="collapseExample">
                                <div class="form-group" id="itemsElement">
                                    <label for="imagen">
                                    </label>
                                    <div id="dropzoneItems" class="dropzone form-control text-center" style="height: auto;">
                                    </div>
                                    <input type="hidden" name="items" id="items" value="{{ old('items') }}">
                                    @error('items')
                                        <div class="text-danger">
                                            {{ $message }}</div>
                                    @enderror
                                    <p id="error"></p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between" class="public_items">
                                <div class="mt-3">
                                    <button class="boton" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseExample" aria-expanded="false"
                                        aria-controls="collapseExample">
                                        <i class="bi bi-file-earmark-image"></i>
                                    </button>
                                </div>
                                <div class=" mt-3">
                                    <input type="submit" class="boton" value="Publicar"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Comunicados -->
            <div class="card p-3 box">
                <div class="row">
                    <div class="col-md-12">
                        <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel"
                            data-bs-interval="15000">
                            <div class="carousel-inner">

                                @if (count($communiquesImage) == 0)
                                    <div class="carousel-item active">
                                        <img src="{{ asset('/img/empy.svg') }}" class="d-block w-100" alt="...">
                                        <div class="carousel-caption d-none d-md-block">
                                        </div>
                                    </div>
                                @else
                                    @foreach ($communiquesImage as $communique)
                                        <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                            <img style="object-fit: contain; max-height: 480px;"
                                                src="{{ asset($communique->image) }}" class="d-block w-100 "
                                                alt="...">
                                            <div class="carousel-caption d-none d-md-block">
                                                <span
                                                    style="background: rgba(3, 42, 51, 0.5); font-size:1.5rem;">{{ $communique->title }}</span>
                                                <br>
                                                <a type="button" class="btn btn-primary buttomItem"
                                                    value="{{ $communique->id }}">Ver mas</a>
                                                @if ($communique->file != null)
                                                    <a class="btn btn-danger " href="{{ asset($communique->file) }}"
                                                        target="_blank">Abrir archivo adjunto</a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Siguiente</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Publicaciones --}}

            @livewire('publications-component')
        </div>

        <!-- Termino de la seccion principal  -->

        <!-- Sidebar  -->
        <div class="col-md-4">

            <!-- App movil  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;"
                style="border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">¡Accede a Promo Connect!</h4>

                <br>
                <div class="d-flex justify-content-center">
                    <img class="mobileLogo" style="width: 50%; height:50%;" src="{{ asset('/img/connect.png') }}"
                        alt="app-movil">
                </div>
                <br>
                <div class="row">
                    <a href="{{ $routeCatalogo }}" type="button" class="btn btn-info" target="_blank">Acceder</a>
                </div>

            </div>

            <!-- Cumpleanos del mes  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;">
                <h4 class="d-flex justify-content-center">Cumpleaños del mes</h4>
                <div class="row">
                    <div class="col">
                        <div id="carousel2" class="carousel slide carousel-fade" data-bs-ride="carousel"
                            data-bs-interval="15000">
                            <div class="carousel-inner">

                                @if (count($employeesBirthday) == 0)
                                    <div class="carousel-item active">
                                        <p class="text-center">Sin cumpleaños disponibles.</p>
                                    </div>
                                @else
                                    @foreach ($employeesBirthday as $employee)
                                        <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                            @if ($employee->user->image == null)
                                                <img style="object-fit: cover; height: 420px;"
                                                    src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                                    class="d-block w-100 " alt="...">
                                            @else
                                                <img style="object-fit: cover; height: 420px;"
                                                    src="{{ asset($employee->user->image) }}" class="d-block w-100 "
                                                    alt="...">
                                            @endif
                                            <div class="carousel-caption d-none d-md-block">
                                                <span
                                                    class="aniversary-text">{{ $employee->user->name . ' ' . $employee->user->lastname }}</span>
                                                <br>
                                                <span
                                                    class="aniversary-text">{{ $employee->birthday_date->format('d/m') }}</span>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel2"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel2"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aniversarios  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;"
                style="border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">¡Gracias por un año más con nosotros!</h4>

                <div class="row">
                    <div class="col">
                        <div id="carousel3" class="carousel slide carousel-fade" data-bs-ride="carousel"
                            data-bs-interval="15000">
                            <div class="carousel-inner">

                                @if (count($employeesAniversary) == 0)
                                    <div class="carousel-item active">
                                        <p class="text-center">Sin aniversarios disponibles.</p>
                                    </div>
                                @else
                                    @foreach ($employeesAniversary as $employee)
                                        <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                            @if ($employee->user->image == null)
                                                <img style="object-fit: cover; height: 420px;"
                                                    src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                                    class="d-block w-100 " alt="...">
                                            @else
                                                <img style="object-fit: cover; height: 420px;"
                                                    src="{{ asset($employee->user->image) }}" class="d-block w-100 "
                                                    alt="...">
                                            @endif
                                            <div class="carousel-caption d-none d-md-block">
                                                <span
                                                    class="aniversary-text">{{ $employee->user->name . ' ' . $employee->user->lastname }}</span>
                                                <br>
                                                <span
                                                    class="aniversary-text">{{ $date - $employee->date_admission->format('Y') . ' ' . 'años' }}</span>
                                            </div>

                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel3"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel3"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <!-- Calendario de eventos  -->
            <div class="card p-4 mt-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;"
                style="border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">Calendario de Eventos</h4>
                <br>
                <div id='calendar'></div>
            </div> --}}

            <!--  Empleado del mes  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;">
                <h4 class="d-flex justify-content-center text-center">Empleado del mes de la Evaluación 360</h4>
                <br>
                <div id="carousel4" class="carousel slide carousel-fade" data-bs-ride="carousel"
                    data-bs-interval="15000">
                    <div class="carousel-inner">

                        @if (count($monthEmployeeController) == 0)
                            <div class="carousel-item active">
                                <p class="text-center">Sin información disponible.</p>
                            </div>
                        @else
                            @foreach ($monthEmployeeController as $employee)
                                <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}">
                                    @if ($employee->photo == null)
                                        <img style="object-fit: cover; height: 420px;"
                                            src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                            class="d-block w-100 " alt="...">
                                    @else
                                        <img style="object-fit: cover; height: 420px;"
                                            src="{{ asset($employee->photo) }}" class="d-block w-100 " alt="...">
                                    @endif
                                    <div class="carousel-caption d-none d-md-block">
                                        <span class="aniversary-text">{{ $employee->name }}</span>
                                        <br>
                                        <div class="d-flex justify-content-center align-items-center">
                                            <p class="card-text m-0 mx-1 aniversary-text">{{ $employee->star }}</p>
                                            <div style="width: 30px;" class="mx-1 aniversary-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @endif
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel4"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel4"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <!-- Ausencias -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px; border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">Ausencias del día de hoy</h4>
                @if (count($empleadosAusentes) > 0)
                    <div class="table-responsive" style="border-radius:10px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color: #DCDCDC" class="text-center">
                                    <th>Colaborador</th>
                                    <th>Responsable</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($empleadosAusentes as $item)
                                    <tr>
                                        <td>{{ $item->name . ' ' . $item->lastname }}</td>
                                        
                                        <td>
                                            @if ($item->reveal != '')
                                                {{ $item->reveal }}
                                            @else
                                                No Disponible
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No tenemos ausencias registradas.</p>
                @endif
            </div>

            <!-- EVENTOS -->
            <div class="card p-4" style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px; border-radius: 20px;">
                <h4 class="d-flex justify-content-center text-center">Eventos del día</h4>
                @if (count($EventosProximosDias) > 0)
                    <div class="table-responsive" style="border-radius:10px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color: #DCDCDC" class="text-center">
                                    <th>Evento</th>
                                    <th>Inicio</th>
                                    <th>Final</th>
                                    <th>Sala</th>
                                    <th>Locación</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($EventosProximosDias as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->start }}</td>
                                        <td>{{ $item->end }}</td>
                                        <td>{{ $item->boordroms->name }}</td>
                                        <td>{{ $item->boordroms->location}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No hay reservaciones en la "Sala Recreativa" el día de hoy.</p>
                @endif
            </div> 

            <!-- Vacaciones porximas  -->
            <div class="card p-4" 
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px; border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">Próximas vacaciones</h4>
                @if (count($proximasVacaciones) > 0)
                    <div class="table-responsive" style="border-radius:10px;">
                        <table class="table table-bordered">
                            <thead>
                                <tr style="background-color: #DCDCDC;" class="text-center">
                                    <th>Colaborador</th>
                                    <th>Responsable</th>
                                    <th>Fechas ausente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proximasVacaciones as $item)
                                    <tr>
                                        <td>
                                            {{ $item->employee->user->name . ' ' . $item->employee->user->lastname }}
                                        </td>
                                        <td>
                                            @if ($item->reveal)
                                                {{ $item->reveal->name . ' ' . $item->reveal->lastname }}
                                            @else
                                                No Disponible
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($item->requestdays as $day)
                                                {{ $day->start }}
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center">No tenemos vacaciones próximas registradas.</p>
                @endif
            </div>
        </div>
    </div>
@stop

@section('styles')
    {{-- Libreria del Calendario de Eventos --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
        integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">


    <style>
        #calendar {
            width: 100%;
        }

        #calendar h2 {
            font-size: 12px;
        }

        #calendar a {
            margin: 0 auto;
            font-size: 16px;
            color: #ffffff;
        }

        td.fc-day.fc-past {
            background-color: #ECECEC;
        }

        .fc-content {
            font-size: 12px;
        }

        .swal-wide {
            width: 50% !important;
        }

        .card {
            border-radius: 8px;
        }

        .public-text {
            text-align: justify;
        }

        .box {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
            border-radius: 10px;

        }

        .aniversary-text {
            background: rgba(3, 42, 51, 0.5);
            font-size: 1.2rem;
        }

        @media screen and (max-width: 768px) {
            #sidebar~#main {
                padding: 0;
            }
        }

        @media (max-width: 530px) {

            .photo_public {
                width: 50%;
            }

        }

        @media (max-width: 420px) {

            .boton {
                padding: 0;
            }

        }
    </style>
@stop


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"
        integrity="sha512-oQq8uth41D+gIH/NJvSJvVB85MFk1eWpMK6glnkg6I7EdMqC1XVkW7RxLheXwmFdG03qScCM7gKS/Cx3FYt7Tg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>


    <script>
        $(document).ready(function() {

            var SITEURL = "{{ url('/') }}";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let noworkingdays = @json($noworkingdays);
            let eventos = @json($eventos);

            console.log(eventos);

            events = []
            noworkingdays.forEach(element => {
                events.push({
                    title: element.reason,
                    start: element.day,
                    description: element.reason,
                    rendering: 'background',
                    editable: false,
                    eventStartEditable: false,
                })
            });

            eventos.forEach(element => {
                events.push({
                    title: element.title,
                    start: element.start,
                    time: element.time,
                    description: element.description,
                    editable: false,
                    eventStartEditable: false,
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
                eventMaxStack: 1,
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                    'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct',
                    'Nov', 'Dic'
                ],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                eventClick: function(event) {

                    Swal.fire({
                        title: event.title,
                        html: '<h4>' + event.description + '</h4><p><strong>Hora: </strong>' +
                            event.time + '</p>',
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

    <script type="text/javascript">
        $(document).ready(function() {
            $('.buttomItem').click(function() {

                let id = $(this).attr("value")
                if (id) {
                    jQuery.ajax({
                        url: '/home/getCommunique/' + id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            console.log(data[0]);

                            if (data[0].file != null) {
                                Swal.fire({
                                    title: '<strong>' + data[0].title + '</strong>',
                                    html: '<img src="' + data[0].image +
                                        '" class="d-block w-100" <br> <br> <h4>  ' +
                                        data[0].description + '</h4>' +
                                        '<a class=" btn btn-link "  href="' + data[0]
                                        .file +
                                        ' " target="_blank">Ver archivo adjunto</a>',
                                    showCloseButton: false,
                                    showCancelButton: false,
                                    focusConfirm: false,
                                    confirmButtonColor: '#006EAD',
                                    confirmButtonText: "Cerrar",
                                    customClass: 'swal-wide',
                                })
                            } else {
                                Swal.fire({
                                    title: '<strong>' + data[0].title + '</strong>',
                                    html: '<img src="' + data[0].image +
                                        '" class="d-block w-100" <br> <br> <h4>  ' +
                                        data[0].description + '</h4>',
                                    showCloseButton: false,
                                    showCancelButton: false,
                                    focusConfirm: false,
                                    confirmButtonColor: '#006EAD',
                                    confirmButtonText: "Cerrar",
                                    customClass: 'swal-wide',
                                })
                            }
                        }
                    });
                } else {
                    alert("No encontrada")
                }

            });
            setTimeout(() => {
                document.querySelector('#messageError').style.display = "none"
            }, 5000);
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        if (document.querySelector('#dropzoneItems')) {
            let items = new Set()
            Dropzone.autoDiscover = false;
            document.addEventListener('DOMContentLoaded', () => {
                // Dropzone
                const dropzoneItem = new Dropzone('#dropzoneItems', {
                    url: "/social/publication/items",
                    dictDefaultMessage: 'Arrastra aqui tus imagenes',
                    acceptedFiles: '.mp4,.avi,.mkv,.png,.jpg,.jpeg,.gif',
                    addRemoveLinks: true,
                    dictRemoveFile: 'Borrar Archivo',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    success: function(file, response) {
                        console.log(file);
                        console.log(response);
                        document.querySelector('#error').textContent = ''
                        items.add(response.correcto)
                        console.log(items);
                        document.querySelector("#items").value = [...items];
                        // Add al objeto de archivo, el nombre de la imagen en el servidor
                        file.nombreServidor = response.correcto
                        // file.previewElement.parentNode.removeChild(file.previewElement)
                    },
                    error: function(file, response) {
                        // console.log(response);
                        // console.log(file);
                        document.querySelector('#error').textContent = 'Formato no valido'
                    },
                    removedfile: function(file, response) {
                        file.previewElement.parentNode.removeChild(file.previewElement)
                        // console.log(file);
                        console.log('El archivo borrado fue');
                        params = {
                            imagen: file.nombreServidor
                        }
                        // console.log(params);
                        axios.post('/social/publication/deleteItem', params)
                            .then(response => {
                                console.log(response.data);
                                if (items.has(response.data.imagen)) {
                                    items.delete(response.data.imagen)
                                    document.querySelector("#items").value = [...items];
                                }
                                console.log(items);
                            })
                    }
                });
            })
        }
    </script>
@stop
