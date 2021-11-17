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
    

    <img class="aboutLogo" src="{{asset('/img/trademarket.png')}}" alt="bhtrade">

    <h3>Misión</h3>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vitae, distinctio. Natus ipsum ratione voluptate harum ex! Sit molestiae, est pariatur magnam cum veniam cupiditate maxime. Doloribus rem quo natus fugit.</p>


    <h3>Visión</h3>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quos, earum velit nisi porro consequatur odit magnam hic tempora exercitationem eaque. Officia nostrum consequatur quod ab tenetur ipsam placeat assumenda minima?</p>


    <h3>Valores</h3>
    <ul>
        <li>Valor 1</li>
        <li>Valor 2</li>
        <li>Valor 3</li>
        <li>Valor 4</li>
        <li>Valor 5</li>
        <li>Valor 6</li>
        <li>Valor 7</li>
        <li>Valor 8</li>
        <li>Valor 9</li>
        <li>Valor 10</li>
    </ul>

@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
@stop