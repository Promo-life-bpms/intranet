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

        //MOSTRAR LOS BOTONES DE MES, SEMANA Y LISTA//
        headerToolbar: {
            left:'prev,next today',
            center:'title',
            right:'dayGridMonth,listWeek',
        },

        //CONFIGURAR COMO SE MUESTRA LAS HORAS//
        slotLabelFormat:{
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
            meridiem: 'short',
        },
        
        dayClick: function(info) {
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
          //Obtenemos la fecha actual//
          var actual = new Date();
          //Formateamos le fecha//
          var año = actual.getFullYear();
          var mes = ('0' + (actual.getMonth() + 1)).slice(-2); // Los meses van de 0 a 11
          var dia = ('0' + actual.getDate()).slice(-2);
          //Unimos el formato//
          var fechaFormateada = año + '-' + mes + '-' + dia;

          //Formateamos le fecha de info.date//
          var date = info.date;
          //Formateamos le fecha//
          var añod = date.getFullYear();
          var mesd = ('0' + (date.getMonth() + 1)).slice(-2); // Los meses van de 0 a 11
          var diad = ('0' + date.getDate()).slice(-2);
          //Unimos el formato//
          var infodateFormateada = añod + '-' + mesd + '-' + diad;

          if(infodateFormateada >= fechaFormateada){
            info.dayEl.style.backgroundColor = '#9DD6AD';
            $("#evento").modal("show");
            //document.getElementById("start").innerHTML= info.dateStr;
          }
          else{
            info.dayEl.style.backgroundColor = '#FFBFAF';
            alert("Error: No se puede crear una reservación de una sala en una fecha pasada.");
          }
        },
        
        ////NOS DIRA CUANTOS EVENTOS PODEMOS APILAR EN LA VISTA PRINCIPAL DEL CALENDARIO////
        dayMaxEventRows: true,
        views: {
          timeGrid: {
            dayMaxEventRows: 3
          }
        },

        //TRADUCCIOÓN DE LOS BOTONES DE MES, SEMANA, LISTA//
        buttonText: {
          today: 'Hoy', // Traducción del botón "Today"
          dayGridMonth: 'Mes',
          listWeek: 'Lista'
        },
            
        initialView: 'dayGridMonth',
        selectable: true,
        events:'/reservation/view/',        

      });
      calendar.render();

});