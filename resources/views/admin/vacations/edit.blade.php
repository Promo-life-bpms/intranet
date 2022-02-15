@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Editar Vacaciones</h3>
            <button class="btn btn-success" onclick="sumar()">Calcular DV</button>
        </div>
    </div>
    <div class="card-body">

        {!! Form::model($vacation, ['route' => ['admin.vacations.update', $vacation], 'method' => 'put']) !!} 

        <div class="row">
           
            <div class="col-md-6">
                <p >Empleado</p>
                <input type="text" id="name" name="name" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos" readonly
                value="{{ $vacation->user->name.' '.$vacation->user->lastname }}">
            </div>

            <div class="col-md-6">
                <p >Fecha de ingreso</p>
                <input type="text" id="date_admission" name="date_admission" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos" readonly
                value="{{ $vacation->user->employee->date_admission }}">
            </div>
        </div>

        <br>

        <div class="row">

            <div class="col-md-4">
                <p >Dias de periodos cumplidos</p>
                <input type="number" id="period_days" name="period_days" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->period_days }}">
            </div> 

            <div class="col-md-4">
                <p >Dias Actuales</p>
                <input type="number" id="current_days" name="current_days" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->current_days }}">
            </div> 

            <div class="col-md-4">
                <p >D.V.</p>
                <input type="number" name="dv" id="dv" class="form-control" step="0.01"
                placeholder="Ingresa los dias de periodo cumplidos"
                value="{{ $vacation->dv }}">

            </div> 

        </div>
        {!! Form::submit('ACTUALIZAR VACACIONES', ['class' => 'btnCreate mt-4']) !!}

        
        {!! Form::close() !!}

     


        {{-- <h5> {{ $user->name . ' ' . $user->lastname }}</h5>
        <br>
        <table class="table">
            <thead>
                <th>#</th>
                <th>Dias/Expiracion</th>
                <th>Eliminar</th>
            </thead>
            <tbody>

                @foreach ($vacations as $vacation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <form method="POST"
                                action="{{ route('admin.vacations.update', ['vacation' => $vacation->id]) }}"
                                accept-charset="UTF-8">
                                <input name="_method" type="hidden" value="PUT">
                                @csrf
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <input type="number" name="days_availables" class="form-control"
                                            placeholder="Ingresa los dias de vacaciones"
                                            value="{{ $vacation->days_availables }}">
                                        @error('days_availables')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                        @enderror
                                    </div>

                                    <div>
                                        <input type="date" name="expiration" class="form-control"
                                            value="{{ $vacation->expiration }}">
                                        @error('expiration')
                                            <small>
                                                <font color="red"> *Este campo es requerido* </font>
                                            </small>
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="submit" value="Actualizar" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.vacations.destroy', ['vacation' => $vacation->id]) }}"
                                method="post">
                                @method('DELETE')
                                @csrf
                                <input type="submit" value="Eliminar" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-center">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-light btn-block" data-bs-toggle="modal"
                            data-bs-target="#modalAddVacations">
                            +
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
 --}}    


        
    </div>


    {{-- <!-- Modal -->
    <div class="modal fade" id="modalAddVacations" tabindex="-1" aria-labelledby="modalAddVacationsLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddVacationsLabel">Agregar Vacaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'admin.vacations.store']) !!}
                <div class="modal-body">
                    <div class="form-group">
                        {!! Form::label('days_availables', 'Dias de vacaciones') !!}
                        {!! Form::number('days_availables', null, ['class' => 'form-control', 'placeholder' => 'Ingresa los dias de vacaciones']) !!}
                        @error('days_availables')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                    <div class="form-group">
                        {!! Form::label('expiration', 'Fecha de vencimiento') !!}
                        {!! Form::date('expiration', null, ['class' => 'form-control', 'placeholder' => 'Ingresa la fecha de vencimiento']) !!}
                        @error('expiration')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <input type="hidden" name="users_id" value="{{ $user->id }}">
                    <input type="submit" value="Guardar" class="btn btn-primary">
                </div>
                {!! Form::close() !!}
                </form>
            </div>
        </div>
    </div> --}}
@stop


@section('styles')
    <style>
        #name>* {
            font-size: 20px;

        }

    </style>
@stop



@section('scripts')
<script>
function sumar(){
    
    var period_days = document.getElementById('period_days').value;
    var current_days = document.getElementById('current_days').value;
    var suma = parseFloat(period_days).toFixed(2) - parseFloat(current_days).toFixed(2);
    var dv = document.getElementById('dv');

    dv.value = parseInt(suma.toFixed(2)) 
  
    
}
</script>

@stop
