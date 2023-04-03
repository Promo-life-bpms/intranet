@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Reservación de la sala recreativa</h1>
                <div id="calendar"></div>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#evento">
                Launch static backdrop modal
            </button>
            <!-- Modal -->
            <div class="modal fade" id="evento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Rerservación</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <from action="">
                                <div class="from-group">
                                    <label for="id">ID:</label>
                                    <input type="text" class="form-control" name="id" id="id" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted">Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="title">Título:</label>
                                    <input type="text" class="form-control" name="title" id="title" aria-describedby="helpId" placeholder="Escribi el título del evento">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="start">Inicio:</label>
                                    <input type="text" class="form-control" name="start" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="end">Fin:</label>
                                    <input type="text" class="form-control" name="end" id="" aria-describedby="helpId" placeholder="">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="people">Número de personas:</label>
                                    <input type="number" class="form-control" name="people" id="" aria-describedby="helpId" placeholder="Número de personas que ocuparan la sala">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="chair">Número de sillas:</label>
                                    <input type="number" class="form-control" name="chair" id="" aria-describedby="helpId" placeholder="Número de sillas que ocuparan en la sala">
                                    <small id="helpId" class="form-text text-muted"> Help text</small>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción del evento:</label>
                                    <textarea class="form-control" name="" id="" cols="30" rows="3"></textarea>
                                </div>                                
                            </from>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="btnGuardar">Guardar</button>
                            <button type="button" class="btn btn-warning" id="btnModificar">Modidicar</button>
                            <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                
                        </div>
                    </div>
                </div>
            </div>
@stop