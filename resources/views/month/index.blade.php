@extends('layouts.app')

@section('content')
    <div class="card-header">
        <h3>Empleado del Mes</h3>
    </div>
    <div class="card-body">
        <div class="d-flex w-100 justify-content-center ">
            <div class="card text-center shadow p-3 mb-5 bg-body rounded">
                <img src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                    alt="Card image cap">
                <h5 class="card-title">Nombre</h5>
                <p class="card-text">Puesto</p>
            </div>
        </div>
        <div class="p-2">
            <h4>Historial</h4>
        </div>
        <table class="table" id="tableRoles">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Puesto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Nombre</td>
                    <td>Puesto</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Nombre</td>
                    <td>Puesto</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Nombre</td>
                    <td>Puesto</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>Nombre</td>
                    <td>Puesto</td>
                </tr>
            </tbody>
        </table>
    </div>

@stop
