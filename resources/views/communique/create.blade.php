@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear comunicado</h3>
    </div>
    <div class="card-body">
        @if (session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif
        <form action="{{ route('communique.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('POST')
            <div class="row">
                <div class="col-md-7">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Titulo</label>
                        <input type="text" class="form-control" name="title"
                            placeholder="Ingrese el titulo del comunicado">
                        @error('title')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
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
                    <div class="mb-2">
                        <label for="formFile" class="form-label">Archivos</label>
                        <input class="form-control" type="file" name="files" id="formFile">

                        @error('image')
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
                <div class="col-md-12">
                    <div class="mb-2">
                        <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
                        <div id="full" class="text-desc">
                            <p>Descripcion del Comunicado!</p>
                            <br>
                        </div>
                        <input type="hidden" name="description" class="text-description">
                        @error('description')
                            <small>
                                <font color="red"> *Este campo es requerido* </font>
                            </small>
                            <br>
                        @enderror
                    </div>
                </div>
            </div>

            <input type="submit" class="btnCreate" value="CREAR COMUNICADO"></button>
        </form>
    </div>

@stop


@section('styles')

    <link rel="stylesheet" href="{{ asset('assets/vendors/quill/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/quill/quill.snow.css') }}">
@stop

@section('scripts')
    <script src="{{ asset('assets/vendors/quill/quill.min.js') }}"></script>
    <script src="{{ asset('assets\js\pages\form-editor.js') }}"></script>
    <script>
        const editor = document.querySelector('.text-desc')
        const textDescription = document.querySelector('.text-description')

        editor.addEventListener('keypress', (e) => {
            textDescription.value = editor.firstChild.innerHTML
        })
    </script>
@stop
