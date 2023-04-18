document.addEventListener('DOMContentLoaded', function() {
    let formulario = document.querySelector("form");
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {    
        initialView: 'dayGridMonth',
        locale:"es",
        hiddenDays: [ 0, 6 ],
        slotMinTime:"08:00:00",
        slotMaxTime: "19:00:00",
        eventColor: '#378006',

        headerToolbar: {
            left:'prev,next today',
            center:'title',
            right:'dayGridMonth,timeGridWeek,listWeek',
        },

        slotLabelFormat:{
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
            meridiem: 'short',
        },

        dateClick:function(info){
            $("#evento").modal("show");
        },

        eventClick: function(info) {
            alert('Event: ' + info.event.title + info.event.start + info.event.end);
            info.el.style.borderColor = 'green';
        },
        
        eventClick: function(info) {
            $('#Editar').modal('show'); // abre el modal
            $('.modal-body').html(info.event.extendedProps.title); // agrega la información del calendario
        },

        initialView: 'dayGridMonth',
        selectable: true,
        events:'/reservation/view/', 
    });

    calendar.render();

});