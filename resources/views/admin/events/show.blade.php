@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Calendario de Eventos</h3>
            <a href="{{ route('admin.events.create') }} " type="button" class="btn btn-success">Agregar</a>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.events.show') }}" method="GET">
            Venue:
            <select name="venue_id">
                <option value="">-- all venues --</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}"
                            @if (request('venue_id') == $venue->id) selected @endif>{{ $venue->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        </form>

        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

        <div id='calendar'></div>


    </div>
</div>
@endsection

@section('scripts')
@parent
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script>
    $(document).ready(function () {
            // page is now ready, initialize the calendar...
            events={!! json_encode($events) !!};
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: events,


            })
        });
</script>
@stop