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
                    <div class="mb-2 form-group">
                    {!! Form::label('days', 'Seleccionar dias') !!}
                    <div id='calendar'></div>
                </div>
                <div>
            </div>
            {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4', 'name'=>'submit']) !!}
        </div>
        {!! Form::close() !!}
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


   
</style>
@stop

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
       
    var SITEURL = "{{ url('/') }}";
      
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      

    let noworkingdays = @json($noworkingdays)

    events = []
    noworkingdays.forEach(element => {
        events.push({
            title: element.reason,
            start: element.day,
            rendering: 'background',
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
                        events: SITEURL + "/events",
                        displayEventTime: false,
                        allDay: true,
                        events,
                        selectable: true,
                        selectHelper: true,
                        select: function (start, end, allDay) {    
                            //Valida si selecciona un dia festivo
                            var dates = start.format('YYYY-MM-DD');
                            var check=false;
                            
                            events.forEach(function(e) {
                            if (dates == e.start){
                                alert("No puedes seleccionar un día festivo")
                                throw BreakException

                            }else{
                                check=true
                            } 
                            });
                            
                         
                            if (check==true) {
                            
                            check=false
                            var title = 'Agregado' 

                            var startDate = moment(start),
                            endDate = moment(end),
                            date = startDate.clone(),
                            isWeekend = false;
                        
                            while (date.isBefore(endDate)) {
                            if (date.isoWeekday() == 6 || date.isoWeekday() == 7) {
                                isWeekend = true;
                                }    
                                date.add(1, 'day');
                            }

                            if (isWeekend) {
                                alert('No se puede seleccionar fin de semana');

                                return false;
                            }else{
                                var start = $.fullCalendar.formatDate(start, "Y-MM-DD");
                                var end = $.fullCalendar.formatDate(end, "Y-MM-DD");
                                
                                if(dateActual<=start){
                                    
                                        $.ajax({
                                        url: SITEURL + "/fullcalenderAjax",
                                        data: {
                                            title: title,
                                            start: start,
                                            end: end,
                                            type: 'add',
                                            
                                        },
                                        type: "POST",
                                        success: function (data) {
                                            displayMessage("Event Created Successfully");
        
                                            calendar.fullCalendar('renderEvent',
                                                {
                                                    id: data.id,
                                                    title: title,
                                                    start: start,
                                                    end: end,
                                                    allDay: allDay,
                                                    
                                                },true);
        
                                            calendar.fullCalendar('unselect');
                                            }
                                        });
                                        
                                }else{
                                    alert('No puedes seleccionar fechas atrasadas ')
                                }
                            }

                                
                               
                            }
                        },
                        eventDrop: function (event, delta) {
                            var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
                            var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD");
      
                            $.ajax({
                                url: SITEURL + '/fullcalenderAjax',
                                data: {
                                    title: event.title,
                                    start: start,
                                    end: end,
                                    id: event.id,
                                    type: 'update'
                                },
                                type: "POST",
                                success: function (response) {
                                    displayMessage("Event Updated Successfully");
                                }
                            });
                        },
                        eventClick: function (event) {
                            var deleteMsg = confirm("Do you really want to delete?");

                            
                            if (deleteMsg) {
                                $.ajax({
                                    type: "POST",
                                    url: SITEURL + '/fullcalenderAjax',
                                    data: {
                                            id: event.id,
                                            type: 'delete'
                                    },
                                    success: function (response) {
                                        calendar.fullCalendar('removeEvents', event.id);
                                        displayMessage("Event Deleted Successfully");
                                    }
                                });
                            }
                        }
     
                    });

    });
     
    function displayMessage(message) {
        toastr.success(message, 'Event');
    } 
      
 </script>

@stop


