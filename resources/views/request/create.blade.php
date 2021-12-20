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
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('type_request', 'Tipo de Solicitud') !!}
                        {!! Form::select('type_request', ['Salir durante la Jornada' => 'Salir durante la Jornada', 'Faltar a sus labores' => 'Faltar a sus labores'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                        @error('type_request')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('payment', 'Forma de Pago') !!}
                        {!! Form::select('payment', ['Descontar Tiempo/Dia' => 'Descontar Tiempo/Dia', 'Pagar Tiempo/Dia' => 'Pagar Tiempo/Dia', 'A cuenta de vacaciones' => 'A cuenta de vacaciones'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                        @error('payment')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('absence', 'Fecha de Ausencia') !!}
                        {!! Form::date('absence', null, ['class' => 'form-control']) !!}
                        @error('absence')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('admission ', 'Fecha de Reingreso') !!}
                        {!! Form::date('admission', null, ['class' => 'form-control']) !!}
                        @error('admission')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <div class="mb-2 form-group">
                        {!! Form::label('reason', 'Motivo') !!}
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el motivo']) !!}
                        @error('reason')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <input type="hidden" id="fechasSeleccionadas">
                    <div id='calendar'></div>
                </div>
            </div>
            {!! Form::submit('Crear solicitud', ['class' => 'btnCreate mt-4']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/fullcalendar/main.min.css') }}">
    <style>
        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 600px;
            margin: 0 auto;
        }

    </style>
@stop

@section('scripts')
    <script src="{{ asset('assets/vendors/fullcalendar/main.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let noworkingdays = @json($noworkingdays)

            events = []
            noworkingdays.forEach(element => {
                events.push({
                    title: element.reason,
                    start: element.day,
                    type: 'noworking'
                })
            });

            let dateActual = moment().format('YYYY-MM-DD');
            const fechasSeleccionadasEl = document.querySelector('#fechasSeleccionadas')
            var calendarEl = document.getElementById('calendar');
            var daysSelecteds = new Set();
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: dateActual,
                selectable: true,
                select: function(arg) {
                    var fecha1 = moment(arg.startStr);
                    var fecha2 = moment(arg.endStr);
                    dateActual = moment(dateActual)
                    if (fecha2.diff(fecha1, 'days') == 1) {
                        if (fecha1.diff(dateActual, 'days') > 0) {
                            if (!daysSelecteds.has(arg.startStr)) {
                                if (fecha1.isoWeekday() !== 6 && fecha1.isoWeekday() !== 7) {
                                    var r = confirm("Seleccionar este dia");
                                    if (r) {
                                        calendar.addEvent({
                                            title: 'OK',
                                            start: arg.start,
                                            startStr: arg.startStr,
                                            end: arg.end,
                                            allDay: arg.allDay
                                        })
                                        daysSelecteds = actualizarFechas(calendar.getEvents())
                                        let lista = [...daysSelecteds]
                                        fechasSeleccionadasEl.value = lista.toString()
                                    }
                                } else {
                                    alert('No puedes seleccionar el fin de semana')
                                }
                            }
                        }
                        calendar.unselect()
                    }
                },
                eventClick: function(arg) {
                    if (confirm('Deseas desmarcar este dia?')) {
                        arg.event.remove()
                        daysSelecteds = actualizarFechas(calendar.getEvents(), 0)
                        let lista = [...daysSelecteds]
                        fechasSeleccionadasEl.value = lista.toString()
                    }
                },
                editable: true,
                dayMaxEvents: 1, // allow "more" link when too many events
                events
            });

            calendar.render();
            calendar.setOption('locale', 'mx');
        });

        function actualizarFechas(events, estado = 1) {
            days = new Set();
            events.forEach(element => {
                days.add(element._def.extendedProps.startStr);
            });
            return days
        }
    </script>
@stop
