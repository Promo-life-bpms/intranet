@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Crear un ticket</h3>
    </div>
    <div class="card-body">
        <h3>Categoria</h3>
        <select name="categoria_id" id="inputCategoria_id" class="form-control">
            <option value="">----------- Escoge una opcion---------------</option>
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria['id'] }}">{{ $categoria['name'] }}</option>
            @endforeach
        </select>
        <div class="mb-3">
            <h3 class="form-label"> Titulo</h3>
            <input class="form-control" type="text" placeholder="" aria-label="default input example">
        </div>

        <h3>Descripcion</h3>
        <textarea class="form-control" style="width:1000px;height:100px" cols="50">Escribe aquí tu comentario: </textarea>
        <div class=" mt-3">
            <input type="submit" class="boton" value="Aceptar"></button>
        </div>
    </div>

@stop
