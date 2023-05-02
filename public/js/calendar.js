document.addEventListener('DOMContentLoaded', function() {
    let formulario = document.querySelector("form");
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {    
        initialView: 'dayGridMonth',
        locale:"es",
        hiddenDays: [ 0, 6 ],
        slotMinTime:"08:00:00",
        slotMaxTime: "19:00:00",
        eventColor: '#6EB5FF',

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
        
        dayClick: function(info) {
          // Llenar automáticamente el campo de fecha en el formulario
          /*formulario.reset();
          formulario.start.value=info.dateStr.format("d-m-Y\TH:i");
          formulario.end.value=info.dateStr.format("d-m-Y\TH:i");*/
          //document.getElementById('start').value = info.dateStr;
          //document.getElementById('end').value = info.dateStr;
          var dateField = document.getElementById('start');
          dateField.value = info.dateStr;
          var dateField = document.getElementById('end');
          dateField.value = info.dateStr;
        },
        
        eventClick: function(info) {
          $('#Editar'+info.event.id).modal('show'); // abre el modal
          ///IMPORTANTE AQUI TENEMOS UN MODAL CONECTADO CON LA VISTA PARA PODER TRAER LA INFORMACÓN POR ID SE LE DEBE POONER "info.event.id"//
        },

        ////NOS ABRE EL MODAL PARA REGISTRAR///
        dateClick: function(info) {
          var actual = new Date();
          //var fechaforma = actual.toISOString().slice(0, 10);
          //console.log(actual);
          if(info.date >= actual){
            info.dayEl.style.backgroundColor = '#9DD6AD';
            $("#evento").modal("show");
            //document.getElementById("start").innerHTML= info.dateStr;
          }
          else{
            info.dayEl.style.backgroundColor = '#FFBFAF';
            alert("Error: No se puede solicitar una cita en una fecha vencida");
          }
        },
        
        ////NOS DIRA CUANTOS EVENTOS PODEMOS APILAR EN LA VISTA PRINCIPAL DEL CALENDARIO////
        dayMaxEventRows: true,
        views: {
          timeGrid: {
            dayMaxEventRows: 3
          }
        },
        
        initialView: 'dayGridMonth',
        selectable: true,
        events:'/reservation/view/',

        
      
      });
      calendar.render();

});