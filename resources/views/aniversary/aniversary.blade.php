@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Aniversarios de {{ $monthAniversary }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($employees as $employee)
                
                    <div class="card aniversary-card" style="width: 240px; height:300px; padding:0;">
                        @if ($employee->user->image != null)
                            <img src="{{ asset($employee->user->image) }}" class="card-img-top" alt="imagen"
                                style="width: 100%; height:180px; object-fit: cover;">
                        @else
                            <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                                style="width: 100%; height:180px; object-fit: cover;" class="card-img-top" alt="imagen">
                        @endif
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title text-center"
                                style=" white-space: wrap; margin-top:10px;  margin-bottom:5px;">
                                {{ $employee->user->name . ' ' . $employee->user->lastname }}</p>
                            {{-- <p class="card-text text-center">{{ Str::substr($employee->date_admission, 0, 10) }}</p> --}}
                            <p class="card-text text-center">Celebra
                                {{ $employee->date_admission->diffInYears($lastDayofMonth) }} años</p>
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
