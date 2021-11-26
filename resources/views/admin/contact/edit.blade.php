@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Editar Contacto</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.contact.update', $contact) }}" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')
            <label for="exampleFormControlInput1" class="form-label mt-4"> Telefono </label>
            <input type="text" class="form-control" maxlength="10" name="num_tel"
                placeholder="Ingrese el numero de telefono" value="{{ $contact->num_tel }}">

            <label for="exampleFormControlInput1" class="form-label mt-4">Correo Promolife </label>
            <input type="text" class="form-control" name="correo1" placeholder="Ingrese el correo"
                value="{{ $contact->correo1 }}">

            <label for="exampleFormControlInput1" class="form-label mt-4">Correo BH-Trademarket </label>
            <input type="text" class="form-control" name="correo2" placeholder="Ingrese el correo"
                value="{{ $contact->correo2 }}">

            <label for="exampleFormControlInput1" class="form-label mt-4">Correo Trademarket </label>
            <input type="text" class="form-control" name="correo3" placeholder="Ingrese el correo"
                value="{{ $contact->correo3 }}">

            <label for="exampleFormControlInput1" class="form-label mt-4">Correo Promodreams </label>
            <input type="text" class="form-control" name="correo4" placeholder="Ingrese el correo"
                value="{{ $contact->correo4 }}">

            <input type="submit" class="btnCreate mt-4" value="ACTUALIZAR CONTACTO"></button>
        </form>
    </div>
@stop

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="{{ asset('/css/styles.css') }}" rel="stylesheet">

@stop

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
@stop
