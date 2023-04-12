@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card-header">
            <h1 style="font-size:20px">Solicitud de Equipo</h1>
            
            <div class="card-body">
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
    
    <form action="{{route('team.createTeamRequest')}}" method="POST">

                {!! Form::open(['route' => 'team.request', 'enctype' => 'multipart/form-data']) !!}
            @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('category', 'Categoria') !!}
                                {!! Form::select('category', ['Equipo de Computo' => 'Equipo de Computo', 'Mobiliario' => 'Mobiliario', 'Equipo Telefonico' => 'Equipo Telefonico', 'Otros' => 'Otros'], 'categoria', ['class' => 'form-control','placeholder' => 'Selecciona la categoria']) !!}
                                @error('category')
                                    <br>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('status', 'Estado') !!}
                                {!! Form::text('status',null, ['class' => 'form-control', 'placeholder' => 'Ingrese el estado']) !!}
                                @error('status')
                                    <br>
                                @enderror
                            </div>
                        </div>



                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('description', 'Descripción') !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Ingrese una descripción']) !!}
                                @error('description')
                                    <br>
                                @enderror
                            </div>
                        </div>
                    </div>
    </div>
        {!! Form::submit('CREAR SOLICITUD', ['class' => 'btnCreate mt-4']) !!}
</div>
        {!! Form::close()!!}
    </form>
        @endsection


