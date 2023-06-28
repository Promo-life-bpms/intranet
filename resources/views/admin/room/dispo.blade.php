@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Disponibilidad de la sala</h1>
                <div id="calendario"></div>
            </div>
            
            @foreach($eventos as $evento)
            @foreach($evento->users as $usuarios)
            @foreach($evento->boordroms as $salas)
            <div class="modal fade" id="modalDetails{{ $evento->id }}" tabindex="-1"aria-labelledby="modalDetails{{ $evento->id }}Label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{($evento->users->name. ' ' .$evento->users->lastname. '.')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"aria-label="Close"></button>
                        </div>
                       
                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Título:</b>
                                {{$evento->title. '.'}}
                            </p>
                        </div>

                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Nombre de la sala:</b>
                                {{$evento->boordroms->name. '.'}}
                             </p>
                        </div>

                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Fecha y hora de inicio:</b>
                                {{$evento->start. '.'}}
                            </p>
                        </div>
                        
                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Fecha y hora de fin:</b>
                                {{$evento->end. '.'}}
                            </p>
                        </div>

                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Invitados:</b>
                                {{$evento->guest.'.'}}
                             </p>
                        </div>
                    
                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Ubicacón de la sala:</b>
                                {{$evento->boordroms->location. '.'}}
                             </p>
                        </div>

                        <div class="modal-body text-left">
                            <p class="m-0">
                                <b>Descripción: </b>
                                {{($evento->description.'.')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endforeach
            @endforeach
@stop