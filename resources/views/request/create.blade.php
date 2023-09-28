@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Solicitar un permiso</h3>
                <div>
                    <!-- Button trigger modal -->
                    {{-- <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        ¿Como solicito mis vacaciones?
                    </button> --}}

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">¿Como solicito mis vacaciones?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $opc = [];
            $ingreso = \Carbon\Carbon::parse(auth()->user()->employee->date_admission);
            $diff = $ingreso->diffInMonths(now());
            $opc = [
                'Salir durante la jornada' => 'Ausencia durante la jornada',
                'Faltar a sus labores' => 'Faltar a sus labores',
                'Fallecimiento de familiar directo' => 'Fallecimiento de familiar directo',
                'Matrimonio del colaborador' => 'Matrimonio del colaborador',
                'Motivos academicas/escolares' => 'Motivos académicos/escolares',
                'Atencion de asuntos personales' => 'Atencion de asuntos personales',
                'Permiso de Paternidad' => 'Permiso de Paternidad',
            ];
            if ($vacations > 0 && $diff > 5) {
                $opc['Solicitar vacaciones'] = 'Solicitar vacaciones';
            }
        @endphp
        <div class="card-body">
            <div class="card shadow text-center">
                <div class="card-body">
                    @if ($vacations >= 0)
                        @if ($vacations > 0)
                            <p class="mt-2" style="font-size: 20px">Días de vacaciones disponibles: <b
                                    id="diasDisponiblesEl">
                                    {{ $vacations }} </b> </p>
                            <p class="mb-2"><b>Actualmente:</b> </p>
                            {{-- @if (auth()->user()->employee->take_expired_vacation)
                                <p class="m-0 text-danger">Tienes <b>
                                        {{ auth()->user()->vacationsComplete()->sum('dv') -
                                            (auth()->user()->vacationsComplete()->where('period', '<', 3)->sum('dv') > 0
                                                ? auth()->user()->vacationsComplete()->where('period', '<', 3)->sum('dv')
                                                : 0) }}
                                    </b> días
                                    disponibles que debes tomar lo mas pronto posible.</p>
                            @endif --}}
                            <br>

                            @foreach ($dataVacations as $item)
                                @if ($item->dv > 0)
                                    @php
                                        $dayFormater = \Carbon\Carbon::parse($item->cutoff_date);
                                        $fecha = $dayFormater->format('d \d\e ') . $dayFormater->formatLocalized('%B') . ' de ' . $dayFormater->format('Y');
                                    @endphp
                                    <p class="m-0">Tienes <b>{{ $item->dv }} </b> días disponibles
                                        {!! $item->period == 1
                                            ? 'de tu período actual y estos días vencen el <b>' . $fecha . '</b>.'
                                            : 'de tu período anterior que vencen el <b>' . $fecha . '</b>.' !!}
                                    </p>
                                @endif
                            @endforeach
                        @else
                            <p style="font-size: 20px">No tienes días de vacaciones disponibles</p>
                        @endif
                    @else
                        <p style="font-size: 20px">No puedes seleccionar vacaciones. Consulta con RRHH para resolver
                            esta situación</p>
                    @endif
                    @if ($diff < 6)
                        <p class="m-0 my-2 text-danger">¡Sin embargo, podras solicitar vacaciones 6 meses después de
                            tu contratación!
                        </p>
                    @endif
                </div>
            </div>
            {!! Form::open(['route' => 'request.store', 'enctype' => 'multipart/form-data']) !!}
            <div class="row">
                <div class=" col-md-6">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="form-group">
                                {!! Form::label('type_request', '¿Cúal es el tipo de solicitud? (Obligatorio)') !!}
                                {!! Form::select('type_request', $opc, null, ['class' => 'form-control', 'placeholder' => 'Seleccione opción']) !!}
                                @error('type_request')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                @enderror
                            </div>
                            <div class="form-group">
                                {!! Form::label('payment', 'Forma de pago (Esta asignación es automática)') !!}
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
                            <div class="{{ old('type_request') == 'Salir durante la jornada' ? '' : 'd-none' }}"
                                id="request_time">
                                <div class="form-group">
                                    {!! Form::label('start', 'Salida') !!}
                                    <br>
                                    <span>Llenar unicamente en caso de retirarse</span>
                                    {!! Form::time('start', null, ['class' => 'form-control']) !!}
                                    @error('start')
                                        <small>
                                            <font color="red"> *Este campo es requerido* </font>
                                        </small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    {!! Form::label('end', 'Ingreso o Reingreso') !!}
                                    <br>
                                    <span>Usar en caso de ingresar despues de las 8:00, o de regresar despues de una
                                        salida</span>
                                    {!! Form::time('end', null, ['class' => 'form-control']) !!}
                                    @error('end')
                                        <small>
                                            <font color="red"> *Este campo es requerido si la salida no tiene un valor*
                                            </font>
                                        </small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-2 form-group" id="opciones"
                                style="{{ old('type_request') == 'Fallecimiento de familiar directo' ? '' : 'display: none;' }}">
                                {{--  --}}
                                {!! Form::label('opcion', 'Selecciona una opcion') !!}
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Conyuge', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion1', 'Conyuge', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Hijos', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion2', 'Hijos', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Padres', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion2', 'Padres', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Hermanos', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion2', 'Hermanos', ['class' => 'form-check-label']) !!}
                                </div>
                                @error('opcion')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                @enderror
                            </div>
                            <div class="mb-2 form-group" id="opcional"
                                style="{{ old('type_request') == 'Motivos academicas/escolares' ? '' : 'display: none;' }}">

                                {!! Form::label('opcion', 'Selecciona ') !!}
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Hijo(a)', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion1', 'Hijo(a)', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check">
                                    {!! Form::radio('opcion', 'Colaborador(a)', false, ['class' => 'form-check-input']) !!}
                                    {!! Form::label('opcion2', 'Colaborador(a)', ['class' => 'form-check-label']) !!}
                                </div>

                                @error('opcion')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                @enderror
                            </div>
                            <div class="mb-2 form-group">
                                {!! Form::label('reason', '¿Cúal es la razón de tu ausencia? (Obligatorio)') !!}
                                <textarea name="reason" cols="30" rows="4" class="form-control"
                                    placeholder="Ingrese las razones de tu ausencia">{{ old('reason') }}</textarea>
                                @error('reason')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                @enderror
                            </div>
                            <div class="mb-2 form-group">

                                {!! Form::label('file', 'Anexa tu justificante: (opcional)') !!}
                                {!! Form::file('archivos_permiso[]', ['class' => 'form-control', 'multiple' => 'multiple']) !!}

                            </div>

                            <div class="mb-2 form-group">
                                <label for="">¿Quién será el responsable de atender tus pendientes?</label>
                                {!! Form::select('reveal', $users, null, ['class' => 'form-control', 'placeholder' => 'Seleccione...']) !!}
                                @error('reveal')
                                    <small>
                                        <font color="red"> {{ $message }} </font>
                                    </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow  h-100">
                        <div class="card-body">
                            <div class="mb-2 form-group">
                                {!! Form::label('days', 'Selecciona los días que no te presentarás a la oficina') !!}
                                <p class="mt'5"></p>
                                <div class="days" id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" text-center">
                {!! Form::submit('Guardar', ['class' => 'btnCreate mt-4', 'name' => 'submit']) !!}
            </div>
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

        .btnCreate {
            display: inline !important;
            width: 25% !important;
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
            let noworkingdays = @json($noworkingdays);
            const SITEURL = "{{ url('/') }}";
            //  Dias totales de vacaciones
            let daysAvailablesToTake = {{ $vacations }};
            // Dias ordenados por periodo y expiracion
            let dataVacations = @json($dataVacations);

            const request_time = document.getElementById('request_time');
            let dateActual = moment().format('YYYY-MM-DD');
            // Mensaje de los dias disponibles del usuario
            const diasDisponiblesEl = document.querySelector('#diasDisponiblesEl')
            let tipoSolicitud = '';
            // Selector del div del calendario
            const calendarEl = document.getElementById('calendar');

            // Asignacion del token a AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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

            let arrayDeMuerte = [];
            let arrayDeMatrimonio = [];
            let arrayDeAtencion = [];
            let arrayDePartenidad = [];

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
                            var start = $.fullCalendar.formatDate(start, "YYYY-MM-DD");
                            var end = $.fullCalendar.formatDate(end, "YYYY-MM-DD");

                            let canSelected = false;

                            let dataVacationsSelected = null;

                            const maxDays = 5;

                            const maxDaysPerYear = 3;
                            let daysWithAsuntosEscolares = {{ $daysWithAsuntosEscolares }};

                            console.log(daysWithAsuntosEscolares);
                            switch (tipoSolicitud) {
                                case 'Solicitar vacaciones':

                                    if (daysAvailablesToTake > 0) {
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
                                        displayError('No tienes dias disponibles')
                                    }
                                    break;
                                case 'Fallecimiento de familiar directo':
                                    if (arrayDeMuerte.length < maxDays) {
                                        arrayDeMuerte.push(start)
                                        canSelected = true;
                                    } else {
                                        canSelected = false
                                        displayError('No puedes seleccionar más de 5 días');
                                    }
                                    console.log(arrayDeMuerte);
                                    break;
                                case 'Matrimonio del colaborador':
                                    if (arrayDeMatrimonio.length < maxDays) {
                                        arrayDeMatrimonio.push(start)
                                        canSelected = true;
                                    } else {
                                        canSelected = false
                                        displayError('No puedes seleccionar más de 5 días');
                                    }
                                    console.log(arrayDeMatrimonio);
                                    break;
                                case 'Atencion de asuntos personales':
                                    let dp = maxDaysPerYear - (daysWithAsuntosEscolares == null ? 0 :
                                        daysWithAsuntosEscolares)
                                    if (arrayDeAtencion.length < dp) {
                                        arrayDeAtencion.push(start)
                                        canSelected = true;
                                    } else {
                                        canSelected = false
                                        displayError('No puedes seleccionar más de 3 días');
                                    }

                                    break;
                                    case 'Permiso de Paternidad':
                                    if (arrayDePartenidad.length < maxDays) {
                                        arrayDePartenidad.push(start)
                                        canSelected = true;
                                    } else {
                                        canSelected = false
                                        displayError('No puedes seleccionar más de 5 días');
                                    }
                                    console.log(arrayDeMatrimonio);
                                    break;
                                default:

                                    canSelected = true
                                    break;
                            }

                            /*     if (tipoSolicitud == 'Solicitar vacaciones') {
                                    if (daysAvailablesToTake > 0) {
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
                                        displayError('No tienes dias disponibles')
                                    }
                                } else {
                                    canSelected = true
                                } */

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
                            if (tipoSolicitud == 'Fallecimiento de familiar directo') {
                                arrayDeMuerte = arrayDeMuerte.filter(date => date != start)
                            }
                            if (tipoSolicitud == 'Matrimonio del colaborador') {
                                arrayDeMatrimonio = arrayDeMatrimonio.filter(date => date !=
                                    start)
                            }
                            if (tipoSolicitud == 'Atencion de asuntos personales') {
                                arrayDeAtencion = arrayDeAtencion.filter(date => date != start)
                            }
                            if (tipoSolicitud == 'Permiso de Paternidad') {

                                arrayDePartenidad = arrayDePartenidad.filter(date => date !=
                                    start)
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
            // Mostrar u ocultar los campos de hora de salida y entrada en el select de tipo de solicitud
            jQuery('select[name="type_request"]').on('change', function() {
                const id = jQuery(this).val();
                tipoSolicitud = id
                data = {}
                if (id == "Solicitar vacaciones") {
                    data.name = 'A cuenta de vacaciones';
                    data.display = 'false';
                } else if (id == "Salir durante la jornada") {
                    data.name = 'Descontar Tiempo/Dia';
                    data.display = 'true';

                } else if (id == 'Faltar a sus labores') {
                    data.name = 'Descontar Tiempo/Dia';
                    data.display = 'false';
                } else {
                    data.name = 'Permiso especial';
                    data.display = 'false';

                }

                if (data.display == "false") {
                    $('#request_time').addClass("d-none");
                    $('#request_time').removeClass("d-block");
                } else {
                    $('#request_time').addClass("d-block");
                    $('#request_time').removeClass("d-none");
                }
                $('.formaPago').val('');
                $('.formaPago').val(data.name)
            });


            // Show/hide the "Fallecimiento de familiar directo" radio buttons based on selection
            jQuery('select[name="type_request"]').on('change', function() {
                const selectedOption = jQuery(this).val();

                // Show the radio buttons if "Fallecimiento de familiar directo" is selected
                if (selectedOption === 'Fallecimiento de familiar directo') {
                    $('#opciones').show();
                } else {
                    // Hide the radio buttons for other options
                    $('#opciones').hide();
                }
                if (selectedOption === 'Motivos academicas/escolares') {
                    $('#opcional').show();
                } else {
                    // Hide the radio buttons for other options
                    $('#opcional').hide();
                }
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
