@extends('layouts.app')

@section('dashboard')
    <div class="row">
        <!-- Seccion principal -->
        <div class="col-md-8">
            <!-- Crear publicacion -->
            <div class="card col-md-12 my-1 py-4 mb-4 box">
                <div class="container-style-main">
                    <div class="container-usuario-publicar d-flex">
                        <div class="avatar avatar-xl">
                            <div class="card-photo" style="width: 45px; height:40px;">
                                @if (auth()->user()->image == null)
                                    <a style="color: inherit;" href="{{ route('profile.index') }}">
                                        <p
                                            class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                            <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                        </p>
                                    </a>
                                @else
                                    <a href="{{ route('profile.index') }}">
                                        <img style="width: 100%; height:100%; object-fit: cover;"
                                            src="{{ asset(auth()->user()->image) }}">
                                    </a>
                                @endif
                            </div>
                        </div>
                        <form action="{{ route('publications.store') }}" class="form-publicar n1-2 flex-grow-1"
                            id="formCreate" method="post">
                            {!! csrf_field() !!}
                            @method('PATCH')
                            @if (session('errorData'))
                                <div class="text-danger" id="messageError">
                                    {{ session('errorData') }}
                                </div>
                            @endif
                            <textarea id="exampleFormControlTextarea1" class="form-control" name="content_publication"
                                placeholder="¿Que estas pensando?"></textarea>
                            <div class="d-flex justify-content-between" class="public_items">
                                {{-- Subir foto/preview --}}
                                {{-- <div class="d-flex upload-photo mt-3">
                                    <div class='file file--upload'>
                                        <label for='input-file'>
                                            Subir imagen
                                        </label>
                                        <input id='input-file' type='file' onchange="readURL(this);" name="photo_public"
                                            class="photo_public" accept="image/*">
                                    </div>

                                    <input type="file" onchange="readURL(this);" name="photo_public" class="photo_public"
                                        accept="image/*">

                                </div> --}}
                                {{-- Subir fotos con dropzone --}}
                                {{-- <div class="form-group" id="itemsElement">
                                    <label for="imagen">
                                        Carga tus imagenes:
                                    </label>
                                    <div id="dropzoneItems" class="dropzone form-control text-center" style="height: auto;">
                                    </div>
                                    <input type="hidden" name="items" id="items">
                                    @error('items')
                                        <div class="text-danger">
                                            {{ $message }}</div>
                                    @enderror
                                    <p id="error"></p>
                                </div> --}}
                                <div class="form-group" id="itemsElement">
                                    <label for="imagen">
                                        Archivos necesarios para la solicitud:
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

                                <div class=" mt-3">
                                    <input type="submit" class="boton" value="Publicar"></button>
                                </div>
                            </div>
                            {{-- <img id="blah" class="img-preview" /> --}}
                        </form>
                    </div>

                    <div class="container-usuarios-publicaciones"></div>
                </div>
            </div>

            <!-- Comunicados -->
            <div class="card p-3 box">
                <div class="row">
                    <div class="col-md-12">
                        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
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
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Publicaciones -->
            <div class="row pl-3 pr-3">
                <div class="col-md-12 p-0">

                    @if (count($publications) <= 0)
                        <p>No hay Publicaciones</p>
                    @else
                        @foreach ($publications as $publication)
                            <div class="m-0 p-0" style="border-radius:20px;">
                                <div class="card p-3 box">
                                    <div class="d-flex head">
                                        <div class="imagen px-1">
                                            <div class="avatar avatar-xl">
                                                <div class="card-photo" style="width: 40px; height:40px;">
                                                    @if (auth()->user()->image == null)
                                                        <a style="color: inherit;"
                                                            href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <p
                                                                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                <span>{{ substr($publication->user->name, 0, 1) . substr($publication->user->lastname, 0, 1) }}</span>
                                                            </p>
                                                        </a>
                                                    @else
                                                        <a style="color: inherit;"
                                                            href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <img style="width: 100%; height:100%; object-fit: cover;"
                                                                src="{{ asset($publication->user->image) }}">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nombre">
                                            <p class="m-0 " style="font-weight: bold">
                                                {{ $publication->user->name . ' ' . $publication->user->lastname }}
                                            </p>
                                            <p class="m-0">
                                                {{ $publication->created_at->diffForHumans() }} </p>
                                        </div>
                                    </div>
                                    <p class="mt-4 ">
                                        {{ $publication->content_publication }} </p>
                                    @if ($publication->photo_public)
                                        <img src="{{ asset('storage') . '/' . $publication->photo_public }}"
                                            alt="" width="auto">
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div id="boton">
                                            <like-button style="margin-top: -24px; overflow:hidden;"
                                                publication-id="{{ $publication->id }}"
                                                like="{{ auth()->user()->meGusta->contains($publication->id) }}"
                                                likes="{{ $publication->like->count() }}">
                                            </like-button>
                                        </div>

                                    </div>

                                    <a style="font-size:18px; color:#000000;" data-bs-toggle="collapse"
                                        href="#collapse{{ $publication->id }}" role="button"
                                        aria-controls="collapse{{ $publication->id }}">
                                        @php
                                            $contador = 0;
                                            foreach ($publication->comments as $comment) {
                                                $contador = $contador + 1;
                                            }
                                        @endphp
                                        Ver comentarios (<?= $contador ?>)
                                    </a>

                                    <div class="collapse mt-4" id="collapse{{ $publication->id }}">
                                        @foreach ($publication->comments as $comment)
                                            <div class="nombre d-flex flex-row">
                                                <div class="com_image">
                                                    <div class="card-photo rounded-circle "
                                                        style="width: 40px; height:40px;">
                                                        @if ($comment->user->image == null)
                                                            <a style="color: inherit;"
                                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                                <p
                                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                                                </p>
                                                            </a>
                                                        @else
                                                            <a style="color: inherit;"
                                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                                    src="{{ $comment->user->image }}">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="com_content">
                                                    <h6 class="ml-4">
                                                        {{ $comment->user->name . ' ' . $comment->user->lastname }}
                                                    </h6>
                                                    <p class="ml-4 public-text">
                                                        {{ $comment->content }}
                                                    </p>
                                                </div>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="com">
                                        <div class="card card-body" style="padding-bottom: 0;">
                                            <form method="POST" action="{{ route('comment') }}" class="comment">
                                                @csrf
                                                <input name="id_publication" id="id_publication" type="hidden"
                                                    value="{{ $publication->id }}">
                                                <div class="form-group row ">
                                                    <div class="col-md-12 align-content-center m-0 p-0">
                                                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror"
                                                            placeholder="Escribe tu comentario..."></textarea>
                                                        @error('content')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="d-flex justify-content-end pr-0">
                                                        <button type="submit" class="boton">
                                                            Comentar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
        <!-- Termino de la seccion principal  -->

        <!-- Sidebar  -->
        <div class="col-md-4">

            <!-- Cumpleanos del mes  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;">
                <h4 class="d-flex justify-content-center">Cumpleaños del Mes</h4>
                <div class="row">
                    <div class="col">
                        <div id="carousel2" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                @if (count($employeesBirthday) == 0)
                                    <div class="carousel-item active">
                                        <p>Sin Cumpleaños disponibles</p>
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
                <h4 class="d-flex justify-content-center text-center">¡Gracias por un año mas con nosotros!</h4>

                <div class="row">
                    <div class="col">
                        <div id="carousel3" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                                @if (count($employeesAniversary) == 0)
                                    <div class="carousel-item active">
                                        <p>Sin Aniversarios disponibles</p>
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
                                                    class="aniversary-text">{{ $employee->date_admission->format('d/m') }}</span>
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

            <!-- Calendario de eventos  -->
            <div class="card p-4 mt-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;"
                style="border-radius:20px;">
                <h4 class="d-flex justify-content-center text-center">Calendario de Eventos</h4>
                <br>
                <div id='calendar'></div>
            </div>

            <!--  Empleado del mes  -->
            <div class="card p-4"
                style="box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;">
                <h4>Empleado del Mes de la Evaluacion 360</h4>
                <br>

                <div class="d-flex flex-wrap w-100 h-30 justify-content-around content-employees">

                    @foreach ($monthEmployeeController as $employeeMonth)
                        <div class="row" style="width: 100%">
                            <div class="card text-center shadow p-3 mx-5 bg-body rounded"
                                style="margin-left:0!important;margin-right:0!important">
                                @if ($employeeMonth->photo == null)
                                    <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                        alt="Card image cap">
                                @else
                                    <img src="{{ asset($employeeMonth->photo) }}" alt="Card image cap"
                                        style="object-fit: cover">
                                @endif
                                <h5 class="card-title">{{ $employeeMonth->name }}</h5>
                                <p class="card-text">{{ $employeeMonth->position }}</p>
                                <div class="d-flex justify-content-center align-items-center">
                                    <p class="card-text m-0 mx-1">{{ $employeeMonth->star }}</p>
                                    <div style="width: 30px;" class="mx-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
@stop

@section('styles')

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css"
        integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

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

        /*         .img-preview {
                                                                                    max-width: 200px;
                                                                                }

                                                                                input[type=file] {
                                                                                    display: none
                                                                                }

                                                                                .file>label {
                                                                                    font-size: 10;
                                                                                    font-weight: 200;
                                                                                    cursor: pointer;

                                                                                    user-select: none;
                                                                                    border-style: solid;
                                                                                    border-radius: 4px;
                                                                                    border-width: 1px;
                                                                                    padding: 6px;

                                                                                    display: flex;
                                                                                    justify-content: center;
                                                                                    align-items: center;
                                                                                }

                                                                                .file {
                                                                                    position: relative;
                                                                                    display: flex;
                                                                                    justify-content: center;
                                                                                    align-items: center;
                                                                                }

                                                                                .file--upload>label {
                                                                                    color: hsl(204, 86%, 53%);
                                                                                    border-color: hsl(204, 86%, 53%);
                                                                                }

                                                                                .file--upload>label:hover {
                                                                                    border-color: hsl(204, 86%, 53%);
                                                                                    background-color: hsl(204, 86%, 96%);
                                                                                }

                                                                                .file--upload>label:active {
                                                                                    background-color: hsl(204, 86%, 91%);

                                                                                } */
    </style>
@stop


@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"
        integrity="sha512-oQq8uth41D+gIH/NJvSJvVB85MFk1eWpMK6glnkg6I7EdMqC1XVkW7RxLheXwmFdG03qScCM7gKS/Cx3FYt7Tg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
    </script>
    <script>
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
    </script>
    <script>
        if (document.querySelector('#dropzoneItems')) {
            let items = new Set()
            Dropzone.autoDiscover = false;
            document.addEventListener('DOMContentLoaded', () => {
                // Dropzone
                const dropzoneItem = new Dropzone('#dropzoneItems', {
                    url: "/social/publication/items",
                    dictDefaultMessage: 'Adjunta las imagenes que quiera compartir',
                    //acceptedFiles: '.pdf,.png,.jpg,.jpeg,.gif,.bmp',
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
