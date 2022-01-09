@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Detalles de la solicitud</h3>
        </div>
        <div class="card-body">
            {!! Form::model($request, ['route' => ['request.authorize.update', $request], 'method' => 'put']) !!}
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        {!! Form::label('type_request', 'Tipo de Solicitud') !!}
                        {!! Form::select('type_request', ['Salir durante la Jornada' => 'Salir durante la Jornada', 'Faltar a sus labores' => 'Faltar a sus labores'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                        @error('type_request')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        {!! Form::label('payment', 'Forma de Pago') !!}
                        {!! Form::select('payment', ['Descontar Tiempo/Dia' => 'Descontar Tiempo/Dia', 'Pagar Tiempo/Dia' => 'Pagar Tiempo/Dia', 'A cuenta de vacaciones' => 'A cuenta de vacaciones'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                        @error('payment')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                         @enderror
                    </div>
                    <div class="row">
                        @role('rh')
                        <div class="col-md-6 mt-4"> 
                            {!! Form::label('human_resources_status', 'Autorizacion de RH') !!}
                            {!! Form::select('human_resources_status', ['Pendiente' => 'Pendiente', 'Aprobada' => 'Aprobada','Rechazada' => 'Rechazada'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                            @error('type_request')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                            @enderror
                        </div>
                        @endrole
    
                        @role('manager')
                        <div class="col-md-6 mt-4">
                            {!! Form::label('direct_manager_status', 'Autorizacion de Jefe directo') !!}
                            {!! Form::select('direct_manager_status', ['Pendiente' => 'Pendiente', 'Aprobada' => 'Aprobada','Rechazada' => 'Rechazada'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione opcion']) !!}
                            @error('type_request')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                            @enderror
                        </div>
                        @endrole
                    </div>
                 

                    <div class="col-md-6 mt-4">
                        {!! Form::label('reason', 'Motivo') !!} 
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'placeholder' => 'Ingrese el motivo','readonly']) !!}
                        @error('reason')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror 
                    </div>

                    <div class="col-md-6 mt-4">
                        {!! Form::label('days', 'Seleccionar dias ') !!}
                        <div class="days" id='calendar'></div>
                        <p>Dias de vacaciones diponibles:  {{$vacations}}  </p>
                    <div>
                </div>
        </div>
        {!! Form::submit('ACTUALIZAR SOLICITUD', ['class' => 'btnCreate mt-4', 'name'=>'submit']) !!}

        {!! Form::close() !!}
    </div>
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
      
    let noworkingdays = @json($noworkingdays);
    let daysSelected = @json($daysSelected);

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

    daysSelected.forEach(element=>{
        events.push({
            title: element.title,
            start: element.start,
            display: 'background',
            editable: false,
        })
     }
    )
    


    let dateActual = moment().format('YYYY-MM-DD');
    const fechasSeleccionadasEl = document.querySelector('#fechasSeleccionadas')
    var calendarEl = document.getElementById('calendar');
    var daysSelecteds = new Set();

    var calendar = $('#calendar').fullCalendar({
                        editable: true,
                        events: SITEURL + "/event",
                        displayEventTime: false,
                        allDay: false,
                        events,
                        selectable: true,
                        selectHelper: true,
                        eventMaxStack:1,
                        select: function (start, end, allDay) {    
                            //Valida si selecciona un dia festivo
                            var dates = start.format('YYYY-MM-DD');
                            var check=false;
                            
                            if(events.length === 0){
                                check=true
                                displayAlert("No hay dias festivos asignados")
                            }

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
                                            displayMessage("Día seleccionado satisfactoriamente");
        
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
                                    displayMessage("Dia actualizado satisfactoriaente");
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
                                        displayMessage("Día borrado satisfactoriamente");
                                    }
                                });
                            }
                        },
                        dayClick: function(date, allDay, jsEvent, view) {
                            $('#calendar').fullCalendar('clientEvents', function(event) {
                                if(event.start <= date && event.end >= date) {
                                    return true;
                                }
                                return false;
                            });
                        }
     
                    });

    });
     
    function displayMessage(message) {
        toastr.success(message, 'Solicitud');
    } 

    function displayAlert(message) {
        toastr.warning(message, 'Advertencia');
    }
      
 </script>

@stop


