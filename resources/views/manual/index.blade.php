@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Manuales informativos</h3>
            @role('rh')
            <a href="{{ route('manual.create') }}" type="button" class="btn btn-success">Agregar</a>
            @endrole
        </div>

    </div>
    <div class="card-body">

        <div class="row">
            <div class="container d-flex flex-wrap">
                <div class="row">

                    @foreach ($manual as $man)
                    @if ($man->img==null)

                        <div class="card text-dark bg-light mb-3 m-2" style="width: 200px; height:auto;" >

                            <img src="{{ asset('img/pdf.png')}}"
                            style="width: 100%; margin-top:10px; height:120px; object-fit: contain;" class="card-img-top" alt="imagen">
                            <div class="card-body" style="padding-top:0; padding-bottom:0">
                                <p class="card-title text-center" style=" white-space: wrap; margin-top:10px;  margin-bottom:5px;">
                                    {{ $man->name }}</p>
                                <a href="{{ $man->file}}" style="width: 100%" target="_blank" class="btn btn-primary mb-2">ABRIR</a>
                            </div>


                            @role('rh')
                            <div class="d-flex flex-row justify-content-center ">
                                <form class="form-delete m-2" action="{{ route('manual.edit', ['manual' => $man->id]) }}"
                                    method="GET">
                                    @csrf
                                    @method('get')
                                    <button type="submit" style="margin: 0"  class="btn btn-primary" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                    <br>
                                </form>

                                <form class="form-delete m-2" action="{{ route('manual.delete', ['manual' => $man->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" style="margin: 0"  class="btn btn-danger" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    <br>
                                </form>
                            </div>
                            @endrole
                        </div>

                    @else

                        <div class="card text-dark bg-light mb-3 m-2" style="width: 200px; height:auto; padding-button:20px;" >

                            <img src="{{ asset($man->img )}}"
                            style="width: 100%; margin-top:10px; height:120px; object-fit: cover;" class="card-img-top" alt="imagen">
                            <div class="card-body" style="padding-top:0; padding-bottom:0">
                                <p class="card-title text-center" style=" white-space: wrap; margin-top:10px;  margin-bottom:5px;">
                                    {{ $man->name }}</p>
                                <a href="{{ $man->file}}" style="width: 100%" target="_blank" class="btn btn-primary mb-2">ABRIR</a>
                            </div>

                            @role('rh')
                            <div class="d-flex flex-row justify-content-center ">

                                <form class="form-delete m-2 mt-0" action="{{ route('manual.edit', ['manual' => $man->id]) }}"
                                    method="GET">
                                    @csrf
                                    @method('get')
                                    <button type="submit" style="margin: 0"  class="btn btn-primary" ><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                    <br>
                                </form>

                                <form class="form-delete m-2 mt-0" action="{{ route('manual.delete', ['manual' => $man->id]) }}"
                                    method="POST">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" style="margin: 0"  class="btn btn-danger" ><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    <br>
                                </form>
                            </div>
                            @endrole

                        </div>

                    @endif

                @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
