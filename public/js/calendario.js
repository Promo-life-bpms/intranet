document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendario');
    var calendar = new FullCalendar.Calendar(calendarEl, {    
        initialView: 'dayGridMonth',
        locale:"es",
        hiddenDays: [ 0, 6 ],
        slotMinTime:"08:00:00",
        slotMaxTime: "19:00:00",
        eventColor: '#9999FF',
      


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

        dayMaxEventRows: true,
        views: {
          timeGrid: {
            dayMaxEventRows: 3
          }
        },

        buttonText: {
          today: 'Hoy', // Traducción del botón "Today"
          dayGridMonth: 'Mes',
          timeGridWeek:'Semana',
          listWeek: 'Lista'
        },
        

        initialView: 'dayGridMonth',
        selectable: true,

        eventClick: function(info) {
            $('#modalDetails'+info.event.id).modal('show'); // abre el modal
            ///IMPORTANTE AQUI TENEMOS UN MODAL CONECTADO CON LA VISTA PARA PODER TRAER LA INFORMACÓN POR ID SE LE DEBE POONER "info.event.id"//
          },

        events: '/dispo/view/',

        
      
      });
      calendar.render();

});