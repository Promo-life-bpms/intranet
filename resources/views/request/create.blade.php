@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Crear solicitud</h3>
        </div>
        <div class="card-body">
            @if (session('message'))
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
            {!! Form::open(['route' => 'request.store']) !!}
            <div class="row">
                <div class=" col-md-6">
                    <div class="form-group">
                        @php
                            $opc = [];
                            if ($vacations >= 0) {
                                $opc = ['Salir durante la jornada' => 'Salir durante la jornada', 'Faltar a sus labores' => 'Faltar a sus labores', 'Solicitar vacaciones' => 'Solicitar vacaciones'];
                            } else {
                                $opc = ['Salir durante la jornada' => 'Salir durante la jornada', 'Faltar a sus labores' => 'Faltar a sus labores'];
                            }
                        @endphp
                        {!! Form::label('type_request', 'Tipo de Solicitud (Obligatorio)') !!}
                        {!! Form::select('type_request', $opc, null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                        @error('type_request')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                        @error('start')
                            <small>
                                <font color="red"> *La hora de salida es requerida* </font>
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('payment', 'Forma de Pago') !!}
                        {!! Form::text('payment', '', [
                            'class' => 'form-control formaPago',
                            'placeholder' => 'Forma de Pago',
                            'readonly',
                        ]) !!}
                        @error('payment')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                    <div class="d-none flex-row form-group" id="request_time">
                        <div class="w-50 mr-1">
                            {!! Form::label('start', 'Salida') !!}
                            {!! Form::time('start', null, ['class' => 'form-control']) !!}
                            @error('start')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                            @enderror
                        </div>

                        <div class="w-50 ml-1">
                            {!! Form::label('end', 'Ingreso (opcional) ') !!}
                            {!! Form::time('end', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="mb-2 form-group">
                        {!! Form::label('reason', 'Motivo (Obligatorio)') !!}
                        <textarea name="reason" cols="30" rows="4" class="form-control" placeholder="Ingrese el motivo"></textarea>
                        @error('reason')
                            <small>
                                <font color="red"> {{ $message }} </font>
                            </small>
                        @enderror
                    </div>
                    <div class="mb-2 form-group">
                        <label for="">Quien estara a cargo en tu ausencia?</label>
                        {!! Form::select('reveal', $users, null, ['class' => 'form-control', 'placeholder' => 'Seleccione...']) !!}
                        @error('reveal')
                            <small>
                                <font color="red"> {{ $message }} </font>
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2 form-group">
                        {!! Form::label('days', 'Seleccionar dias ') !!}
                        <div class="days" id='calendar'></div>
                        @if ($vacations >= 0)
                            <p class="mt-2">Dias de vacaciones diponibles: <b id="diasDisponiblesEl">
                                    {{ $vacations }} </b> </p>
                            <p class="m-0 mt-2 text-danger">Importante!</p>
                            @foreach ($dataVacations as $item)
                                <p class="m-0"><b>{{ $item->dv }} </b> dias disponibles hasta el <b>
                                        {{ $item->cutoff_date }} </b> </p>
                            @endforeach
                        @else
                            <p>No puedes seleccionar vacaciones. Consulta con RRHH para resolver esta situacion</p>
                        @endif
                    </div>
                </div>
            </div>
            {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4', 'name' => 'submit']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />

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
    </style>
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            // Constantes
            const request_time = document.getElementById('request_time');
            const SITEURL = "{{ url('/') }}";
            let noworkingdays = @json($noworkingdays);
            let dateActual = moment().format('YYYY-MM-DD');
            // Mensaje de los dias disponibles del usuario
            const diasDisponiblesEl = document.querySelector('#diasDisponiblesEl')
            //  Dias totales de vacaciones
            let daysAvailablesToTake = {{ $vacations }};
            let tipoSolicitud = '';
            // Dias ordenados por periodo y expiracion
            let dataVacations = @json($dataVacations);
            // Selector del div del calendario
            const calendarEl = document.getElementById('calendar');

            // Dias Seleccionados si guardarse en la DB
            let daysSelecteds = new Set();

            // Asignacion del token a AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Mostrar u ocultar los campos de hora de salida y entrada en el select de tipo de solicitud
            jQuery('select[name="type_request"]').on('change', function() {
                const id = jQuery(this).val();
                tipoSolicitud = id
                data = {}
                if (id == "Solicitar vacaciones") {
                    data.name =  'A cuenta de vacaciones';
                    data.display =  'false';
                } else if (id == "Salir durante la jornada") {
                    data.name =  'Descontar Tiempo/Dia';
                    data.display =  'true';
                } else {
                    data.name =  'Descontar Tiempo/Dia';
                    data.display =  'false';
                }

                if (data.display == "false") {
                    $('#request_time').addClass("d-none");
                    $('#request_time').removeClass("d-flex");
                } else {
                    $('#request_time').addClass("d-flex");
                    $('#request_time').removeClass("d-none");
                }
                $('.formaPago').val('');
                $('.formaPago').val(data.name)
            });

            // Asignacion de los dias no laborales
            let events = []
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

            // Arreglo de los dias disponibles y su expiracion
            const vacationsExpirationsFinally = dataVacations.map(data => {
                return {
                    cutoff_date: data.cutoff_date,
                    dv: data.dv
                }
            })
            let vacationsExpirations = dataVacations.map(data => {
                return {
                    cutoff_date: data.cutoff_date,
                    dv: data.dv
                }
            })

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/event",
                displayEventTime: false,
                allDay: false,
                events,
                selectable: true,
                selectHelper: true,
                dragScroll: false,
                eventMaxStack: 1,
                nextDayThreshold: '00:00:00',
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                    'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ],
                monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct',
                    'Nov', 'Dic'
                ],
                dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                select: function(start, end, allDay) {
                    // Variable de estado (Revisa si la fecha se puede seleccionar)
                    let check = false;
                    //Valida si selecciona un dia no laborable
                    const dates = start.format('YYYY-MM-DD');
                    events.forEach(function(e) {
                        console.log(start);
                        if (dates == e.start) {
                            displayInfo("No puedes seleccionar un día festivo")
                            throw BreakException
                        } else {
                            check = true
                        }
                    });
                    if (events.length === 0) {
                        check = true
                    }
                    if (check == true) {
                        check = false
                        let title = 'Ausente'
                        var startDate = moment(start),
                            endDate = moment(end),
                            date = startDate.clone(),
                            isWeekend = false;
                        end = $.fullCalendar.moment(start);
                        end.add(1, 'hours');

                        while (date.isBefore(endDate)) {
                            if (date.isoWeekday() == 6 || date.isoWeekday() == 7) {
                                isWeekend = true;
                            }
                            date.add(1, 'day');
                        }
                        if (isWeekend) {
                            displayInfo('No se puede seleccionar fin de semana');
                            return false;
                        } else {
                            var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                            var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                            if (daysAvailablesToTake > 0) {
                                let canSelected = false;
                                let dataVacationsSelected = null;
                                if (tipoSolicitud == 'Solicitar vacaciones') {
                                    if (vacationsExpirations.length == 2) {
                                        if (start <= vacationsExpirations[0].cutoff_date && start <=
                                            vacationsExpirations[1].cutoff_date) {
                                            if (vacationsExpirations[0].dv > 0) {
                                                canSelected = true
                                                dataVacationsSelected = 0
                                            } else if (vacationsExpirations[1].dv > 0) {
                                                canSelected = true
                                                dataVacationsSelected = 1
                                            } else {
                                                canSelected = false
                                            }
                                        } else if (start > vacationsExpirations[0].cutoff_date &&
                                            start <=
                                            vacationsExpirations[1].cutoff_date) {
                                            if (vacationsExpirations[1].dv > 0) {
                                                canSelected = true
                                                dataVacationsSelected = 1
                                            } else {
                                                displayAlert('No puedes seleccionar este dia')
                                            }
                                        } else if (start > vacationsExpirations[0].cutoff_date) {
                                            displayAlert('No puedes seleccionar este dia')
                                        }
                                    } else {
                                        if (start <= vacationsExpirations[0].cutoff_date) {
                                            if (vacationsExpirations[0].dv > 0) {
                                                canSelected = true
                                                dataVacationsSelected = 0
                                            } else {
                                                canSelected = false
                                            }
                                        } else if (start > vacationsExpirations[0].cutoff_date) {
                                            displayAlert('No puedes seleccionar este dia')
                                        }
                                    }
                                } else {
                                    canSelected = true
                                }
                                if (canSelected) {
                                    $.ajax({
                                        url: SITEURL + "/fullcalenderAjax",
                                        data: {
                                            title: title,
                                            start: start,
                                            end: end,
                                            allDay: false,
                                            type: 'add',
                                        },
                                        type: "POST",
                                        success: function(data) {
                                            if (data.exist) {
                                                displayError(
                                                    'Ya has seleccionado este dia'
                                                )
                                                return;
                                            }
                                            calendar.fullCalendar('renderEvent', {
                                                id: data.id,
                                                title: title,
                                                start: start,
                                                end: end,
                                                allDay: false,
                                            }, true);
                                            if (tipoSolicitud == 'Solicitar vacaciones') {
                                                daysAvailablesToTake--
                                                vacationsExpirations[dataVacationsSelected]
                                                    .dv--;
                                                canSelected = false
                                                diasDisponiblesEl.innerHTML =
                                                    daysAvailablesToTake
                                            }
                                            displayMessage(
                                                "Día seleccionado satisfactoriamente"
                                            );
                                            calendar.fullCalendar('unselect');
                                        }
                                    });
                                }
                            } else {
                                displayError('No tienes dias disponibles')
                            }
                        }

                    }
                },
                eventClick: function(event) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡El día seleccionado se eliminará!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '¡Si, eliminar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            let start = event.start._i
                            let dataVacationsSelected = null;
                            if (tipoSolicitud == 'Solicitar vacaciones') {
                                if (vacationsExpirationsFinally.length == 2) {
                                    if (start <= vacationsExpirations[0].cutoff_date) {
                                        if (vacationsExpirations[0].dv <
                                            vacationsExpirationsFinally[0]
                                            .dv) {
                                            dataVacationsSelected = 0
                                        } else if (vacationsExpirations[1].dv <
                                            vacationsExpirationsFinally[1]
                                            .dv) {
                                            dataVacationsSelected = 1
                                        }
                                    } else if (start > vacationsExpirations[0].cutoff_date &&
                                        start <=
                                        vacationsExpirations[1].cutoff_date) {
                                        if (vacationsExpirations[1].dv <
                                            vacationsExpirationsFinally[1]
                                            .dv) {
                                            dataVacationsSelected = 1
                                        }
                                    }
                                } else {
                                    dataVacationsSelected = 0
                                }
                            }
                            $.ajax({
                                type: "POST",
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    id: event.id,
                                    type: 'delete'
                                },
                                success: function(response) {
                                    calendar.fullCalendar('removeEvents', event.id);
                                    if (tipoSolicitud == 'Solicitar vacaciones') {
                                        daysAvailablesToTake++
                                        vacationsExpirations[dataVacationsSelected]
                                            .dv++;
                                        diasDisponiblesEl.innerHTML =
                                            daysAvailablesToTake
                                    }
                                    displayMessage(
                                        "Día borrado satisfactoriamente");
                                }
                            });
                        }
                    })
                },

            });
        });

        function displayMessage(message) {
            toastr.success(message, 'Solicitud');
        }

        function displayAlert(message) {
            toastr.warning(message, 'Advertencia');
        }

        function displayInfo(message) {
            toastr.info(message, 'Advertencia');
        }

        function displayError(message) {
            toastr.error(message, 'Error');
        }
    </script>
@stop
