@extends('layouts.app')

@section('content')

    <div class="card-body">
        @php
            $randomKey = time();
        @endphp
        @livewire('vacations.vacations', [], key($randomKey))
    </div>
@stop
