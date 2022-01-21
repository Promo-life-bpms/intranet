@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Vacaciones</h3>
    </div>
    <div class="card-body">
        <h5> {{ $user->name . ' ' . $user->lastname }}</h5>
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
    </div>


    <!-- Modal -->
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
    </div>
@stop


@section('styles')
    <style>
        #name>* {
            font-size: 20px;

        }

    </style>
@stop



@section('scripts')

@stop
