@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Disponibilidad de la sala</h1>
            </div>

            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Prueba</h5>
                    <from class="formulario" action="{{route('reserviton.creative.create')}}" method="POST">
                                {{ method_field('post') }}
                                {{ csrf_field() }}
                                @csrf
                                <div class="form-group">
                                    <label for="name">Nombre sala:</label>
                                    <input type="text" class="form-control" name="name" id="" aria-describedby="helpId" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="location">Ubicación:</label>
                                    <input type="text" class="form-control" name="location" id="" aria-describedby="helpId" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="capacitance">Fin:</label>
                                    <input type="text" class="form-control" name="capacitance" id="" aria-describedby="helpId" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="description">Descripción del evento:</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="3"></textarea>
                                </div>  
                                     
                                <button type="button" class="btn btn-success">Crear</button>
                            </from>
                </div>
            </div>
@stop