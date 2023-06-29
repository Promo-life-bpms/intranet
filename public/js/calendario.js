document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendario');
  var calendar = new FullCalendar.Calendar(calendarEl, {    
      initialView: 'timeGridWeek', // Mostrar solo la vista de "Semana"
      locale: "es",
      hiddenDays: [0, 6],
      slotMinTime: "08:00:00",
      slotMaxTime: "19:00:00",
      eventColor: '#9999FF',
      
      headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: ''
      },
      
      slotLabelFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: true,
          meridiem: 'short'
      },
      
      dayMaxEventRows: true,
      
      buttonText: {
          today: 'Hoy',
          dayGridMonth: 'Mes',
          timeGridWeek: 'Semana',
          listWeek: 'Lista'
      },
      
      selectable: true,
      
      eventClick: function(info) {
          $('#modalDetails' + info.event.id).modal('show');
      },
      
      events: '/dispo/view/'
  });
  
  calendar.render();
});
