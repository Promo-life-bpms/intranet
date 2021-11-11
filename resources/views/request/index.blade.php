@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Crear solicitud</h1>
@stop

@section('content')


<div class="contenedor-logo">
        <ul class="logos" style="padding-left: 10px;">
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/bhtrade.png')}}"  alt="bhtrade"></a> </li>
            <li><a  href="#"><img style="max-width: 80px;" src="{{asset('/img/promolife.png')}}"  alt="promolife"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;"src="{{asset('/img/promodreams.png')}}"  alt="promodreams"></a> </li>
            <li><a  href="#"><img style="max-width: 50px;" src="{{asset('/img/trademarket.png')}}"  alt="trademarket"></a> </li>
        </ul>
    </div>
    
<form >
  <div class="form-group">
    <div class="row">
        <div class="col">
        <label for="formGroupExampleInput">Nombre</label>
      <input type="text" class="form-control" placeholder="Ingrese su nombre">
    </div>
    <div class="col">
        <label for="formGroupExampleInput">Fecha Solicitud</label>
        <input type="date" class="form-control" " placeholder="Last name">
    </div>
  </div>
  </div>
  <div class="form-group">
    <div class="row">
        <div class="col">
        <label for="formGroupExampleInput">Solicitud</label>
      <input type="text" class="form-control" placeholder="Ingrese su nombre">
    </div>
    <div class="col">
        <label for="formGroupExampleInput">Dia de Aplicación</label>
        <input type="date" class="form-control"  placeholder="Last name">
    </div>
  </div>
  <div class="form-group">
    <div class="form-check form-check-inline" style="margin-top: 20px;">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
        <label class="form-check-label" for="inlineRadio1">Descontar Tiempo/Dia</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
        <label class="form-check-label" for="inlineRadio2">A Cuenta de Vacaciones</label>
    </div>
  </div>
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Motivo</label>
    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
  </div>
  <button type="button" class="btn btn-primary btn-lg btn-block">ENVIAR</button>
</form>
@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}"  rel="stylesheet">

@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop