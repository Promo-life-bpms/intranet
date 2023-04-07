@extends('layouts.app')

@section('content')
            <div class ="container">
                <h1>Prueba</h1>
            </div>

            <body>
                <form method="POST" action="{{route('prueba.vista.create')}}"></form>
                    @csrf
                    <label for="Nombre">Nombre:</label>
                    <input type="text" name="nombre" id="Nombre">
                    <br>
                    <label for="ApePaterno">Apellido Paterno:</label>
                    <input type="text" name="ApePaterno" id="ApePaterno">
                    <br>
                    <label for="ApeMaterno">Apellido Materno:</label>
                    <input type="text" name="ApeMaterno" id="ApeMaterno">
                    <br>
                    <label for="NumeroCel">Numero de celular:</label>
                    <input type="text" name="NumeroCel" id="NumeroCel">
                    <br>
                    <label for="Correo">Correo electronico:</label>
                    <input type="email" name="Correo" id="Correo">
                    <br>
                    <input type="submit" value="Guardar datos">
                </form>
            </body>


@stop