document.addEventListener('DOMContentLoaded', function() {

    let formulario =document.querySelector("form");
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locales:"es",


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
        }
    });

    
    calendar.render();
 


    document.getElementById("btnGuardar").addEventListener("click", function(){
        const datos =new FormData(formulario);
        console.log(datos);
        axios.post(route('reserviton.creative.create'), datos).
        then((respuesta) =>{
            $("#evento").modal("hide");

        }
        ).catch(
            error=>{
                if(error.response){
                    console.log(error.response.data);
                }
            }
        )

    });
});

