@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Cumpleaños de {{$monthBirthday}}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            @foreach ($employees as $employee)
                <div class="col-md-3">
                    <div class="card" style="width: 200px; height:220px;">
                        @if ($employee->user->image !=null)
                            <img src="{{ asset($employee->user->image )}}"
                            style="width: 100%; height:150px;   object-fit: contain;" class="card-img-top" alt="imagen">
                        @else 
                            <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg"
                            style="width: 100%; height:150px;   object-fit: contain;" class="card-img-top" alt="imagen">
                        @endif
                        <div class="card-body" style="padding-top:0; padding-bottom:0">
                            <p class="card-title text-center" style=" white-space: nowrap; margin-bottom:5px;">
                                {{ $employee->user->name . ' ' . $employee->user->lastname }}</p>
                            <p class="card-text text-center">{{ $employee->birthday_date }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
