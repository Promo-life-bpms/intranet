@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3 class="text-center">Empleados del Mes de la Evaluacion 360</h3>
    </div>
    <div class="card-body">
        <div class="d-flex w-100 justify-content-center content-employees">
            @foreach ($monthEmployeeController as $employeeMonth)
                <div class="card text-center shadow p-3 mx-5 bg-body rounded">
                    @if ($employeeMonth->photo==null)
                    <img src="https://image.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg" alt="Card image cap" style="height: 200px;object-fit: cover; overflow:hidden;">
                    @else
                        <img src="{{ asset($employeeMonth->photo) }}" alt="Card image cap" style="max-height: 200px; object-fit: cover;">
                    @endif
                    <h5 class="card-title">{{ $employeeMonth->name }}</h5>
                    <p class="card-text">{{ $employeeMonth->position }}</p>
                    <div class="d-flex justify-content-center align-items-center">
                        <p class="card-text m-0 mx-1">{{ $employeeMonth->star }}</p>
                        <div style="width: 30px;" class="mx-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop
