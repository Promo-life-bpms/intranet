@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Directorio de vacaciones</h3>

            <a style="margin-left: 20px;" href=" {{ route('admin.vacations.export') }} " type="button"
                class="btn btn btn-success">Exportar Excel</a>
        </div>
    </div>
    <div class="card-body">
        @php
            $randomKey = time();
        @endphp
        @livewire('vacations.vacations', [], key($randomKey))
    </div>
@stop
