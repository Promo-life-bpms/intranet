@extends('layouts.app')

@section('dashboard')

<div class="contenedor-logo">
        <ul class="logos" style="padding-left: 10px;">
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/bhtrade.png')}}"  alt="bhtrade"></a> </li>
            <li><a  href="#"><img style="max-width: 80px;" src="{{asset('/img/promolife.png')}}"  alt="promolife"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;"src="{{asset('/img/promodreams.png')}}"  alt="promodreams"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/trademarket.png')}}"  alt="trademarket"></a> </li>
        </ul>
    </div>


    <h3>Empleados</h3>
    <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col"># </th>
      <th scope="col">Nombre</th>
      <th scope="col">Apellido Paterno</th>
      <th scope="col">Apellido Materno</th>
      <th scope="col">Cumpleaños</th>
      <th scope="col">Ingreso</th>
      <th scope="col">Status</th>
      <th scope="col">ID usuario</th>
      <th scope="col">Opciones</th>
      
    </tr>
  </thead>
  <tbody>
  @foreach($employees as $employee)
    <tr>
      <th>{{$employee->id}}</th>
      <td>{{$employee->nombre}}</td>
      <td>{{$employee->paterno}}</td>
      <td>{{$employee->materno}}</td>
      <td>{{$employee->fecha_cumple	}}</td>
      <td>{{$employee->fecha_ingreso}}</td>
      <td>{{$employee->status}}</td>
      <td>{{$employee->id_user}}</td>
      <td>
        <button type="button" class="btn btn-primary">EDITAR</button>
        <button type="button" class="btn btn-danger">ELIMINAR</button>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>

@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop