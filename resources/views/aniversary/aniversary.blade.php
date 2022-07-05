@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Aniversarios de {{ now()->formatLocalized('%B') }}</h3>
    </div>
    <div class="card-body">
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
                        @php
                            $years = $employee->date_admission->diffInYears($lastDayofMonth);
                        @endphp
                        <p class="card-text text-center">Celebra
                            {{ $years }} año{{ $years > 1 ? 's' : '' }}</p>
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
