@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Eventos</h3>
        </div>
    </div>
    <div class="card-body">

        <div class="container">

         <div id='calendar' class="mt-4"></div>
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
            display: 'background',
            editable: false,
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
                            var title = 'Agregado' /* prompt('Event Title:'); */
                            if (title) {
                                

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
                                            type: 'add'
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
                                                    allDay: allDay
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


