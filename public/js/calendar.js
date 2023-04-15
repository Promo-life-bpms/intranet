document.addEventListener('DOMContentLoaded', function() {
    let formulario = document.querySelector("form");
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
    
        initialView: 'dayGridMonth',
        locale:"es",
        hiddenDays: [ 0, 6 ],
        minTime:"08:00:00",
        maxTime: "16:00:00",
        initialView: 'dayGridMonth',
        selectable: true,

        

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

    events:'http://127.0.0.1:8000/reservation/view/',
    dateClick:function(info){
        $("#evento").modal("show");
    },
    

    eventClick: function(info) {
        alert('Event: ' + info.event.title + info.event.start + info.event.end);
        info.el.style.borderColor = 'blue';
      },
      
    
    });

    
    calendar.render();


 
});

