@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar comunicado</h3>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('communique.update', $communique) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-md-7">

                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Titulo </label>
                        <input type="text" class="form-control" name="title" placeholder="Ingrese el titulo del comunicado"
                            value="{{ $communique->title }}">

                        @error('title')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="mb-2">
                                <label for="formFile" class="form-label">Imagen</label>
                                <input class="form-control" type="file" name="image" id="formFile">

                                @error('image')
                                    <small>
                                        <font color="red"> *Este campo es requerido* </font>
                                    </small>
                                    <br>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="4"
                            name="description">{{ $communique->description }}</textarea>

                        @error('description')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror

                    </div>

                </div>
                <div class="col-md-5">
                    <div class="row mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Enviar a</label>
                        <div class="d-flex">
                            @foreach ($companies as $company)
                                <div class="form-check mx-1">
                                    <input class="form-check-input" type="checkbox" name="companies[]"
                                        value="{{ $company->name_company }}" id="checkcompany{{ $company->id }}">
                                    <label class="form-check-label" for="checkcompany{{ $company->id }}">
                                        {{ $company->name_company }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="formFile" class="form-label">Departamento</label>
                        @foreach ($departments as $department)
                            <div class="form-check mx-3">
                                <input class="form-check-input" type="checkbox" name="departments[]"
                                    value="{{ $department->name }}" id="checkdepartment{{ $department->id }}">
                                <label class="form-check-label" for="checkdepartment{{ $department->id }}">
                                    {{ $department->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <input type="submit" class="btnCreate" value="ACTUALIZAR COMUNICADO"></button>
        </form>

    </div>
@stop
