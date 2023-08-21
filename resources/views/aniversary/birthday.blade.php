@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Cumpleaños de {{ now()->formatLocalized('%B') }}</h3>
    </div>
    <div class="card-body">

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            <br>
        @endif
        <div class="row justify-content-center">
            @foreach ($employees as $employee)
                <div class="card aniversary-card" style="width: 16rem;">
                    @if ($employee->user->image != null)
                        <img src="{{ asset($employee->user->image) }}"
                            style="width: 100%; object-position:top center; height:220px; object-fit: cover;"
                            class="card-img-top" alt="imagen">
                    @else
                        <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                            style="width: 100%; object-position:top center; height:220px; object-fit: cover;"
                            class="card-img-top" alt="imagen">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title text-center"> {{ $employee->user->name . ' ' . $employee->user->lastname }}
                        </h5>
                        <p class="card-text text-center">
                            {{ $employee->birthday_date->format('d \d\e ') . $employee->birthday_date->formatLocalized('%B') }}
                        </p>

                        @role('admin')

                            <div class="d-flex justify-content-center">
                                <form method="POST" action="{{ route('firebase.birthday',['user_id' => $employee->user->id, 'name' => $employee->user->name]) }}">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary ">Enviar felicitación</button>.
                                </form>
                            </div>
                        @endrole('admin')
                        
                        @role('rh')

                            <div class="d-flex justify-content-center">
                            <form method="POST" action="{{ route('firebase.birthday',['user_id' => $employee->user->id, 'name' => $employee->user->name]) }}">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-primary ">Enviar felicitación</button>.
                                </form>
                            </div>
                        @endrole('rh')
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('styles')
    <style>
        .aniversary-card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin: 20px;
        }
    </style>
@stop
