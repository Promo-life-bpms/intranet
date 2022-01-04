@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Calendario de Eventos</h3>
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.events.index') }} " type="button" class="btn btn-success" style="margin-right: 10px;">Regresar</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div  id='calendar'></div>
    </div>
</div>
@endsection
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
        font-size: 1.8rem;
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
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

