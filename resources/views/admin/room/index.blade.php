<script src="/node_modules/@fullcalendar/core/main.js"></script>
<script src="/node_modules/@fullcalendar/daygrid/main.js"></script>
<script src="/node_modules/@fullcalendar/timegrid/main.js"></script>
<script src="/node_modules/@fullcalendar/list/main.js"></script>

@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Reservación de la sala recreativa</h1>
                <div id="calendar"></div>
            </div>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#evento">
                Launch static backdrop modal
            </button>
            
            <div class="modal fade" id="evento" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rerservación</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <from class="formulario" action="{{route('reserviton.creative.create')}}" method="POST">
                                {{ method_field('post') }}
                                {{ csrf_field() }}
                                @csrf
                                <div class="form-group">
                                    <label for="">usuario:</label>
                                    <select name="id_usuario" id="inputName_usuario" class="form-control">
                                        @foreach ($usuarios as $usuario)
                                            <option value="{{isset($usuario['id'])}}">{{$usuario['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Sala:</label>
                                    <select name="id_sala" id="inputName_sala" class="form-control">
                                        <option value="">SELECCIONE LA SALA</option>
                                        @foreach ($salas as $sala)
                                            <option value="{{isset($sala['id'])}}">{{$sala['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="date">Fecha:</label>
                                    <input type="date" class="form-control" name="date" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="start_time">Inicio:</label>
                                    <input type="time" class="form-control" name="start_time" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="end_time">Fin:</label>
                                    <input type="time" class="form-control" name="end_time" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="number_of_people">Número de personas:</label>
                                    <input type="number" class="form-control" name="number_of_people" id="" aria-describedby="helpId" placeholder="Número de personas que ocuparan la sala">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="material">Material:</label>
                                    <input type="text" class="form-control" name="material" id="" aria-describedby="helpId" placeholder="Material que utilizará">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="chair_loan">Número de sillas:</label>
                                    <input type="number" class="form-control" name="chair_loan" id="" aria-describedby="helpId" placeholder="Número de sillas que ocuparan en la sala">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>          

                                <div class="form-group">
                                    <label for="description">Descripción del evento:</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="3"></textarea>
                                </div>  
                                     
                                <input type="reset"  class="btn btn-success" value="guardar"/>
                            </from>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" >Modidicar</button>
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                
                        </div>
                    </div>
                </div>
            </div>

@stop



<script>
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
            },

            select: function(start){
                var date=new Date(start);
                var hoy =new Date();
                var ayer= new Date(hoy - 24 * 60 * 60 * 1000);
                console.log("ayer:" + ayer);
                console.log("hoy:" + hoy);
                console.log("start:" + date);

                if(date>ayer){

                }
                else{
                    alert("No se puede agregar una reservación")
                }

            }
            
        });
        calendar.render();
        });
</script>
