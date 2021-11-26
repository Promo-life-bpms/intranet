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

<div class="row">
  <div class="col-8 ">
    <h3>Roles</h3>
  </div>

  <div class="col-4">
    <a href="{{route('admin.roles.create')}}" type="button" style="width: 100%;" class="btn btn-success">AGREGAR NUEVO</a>
  </div>
</div>
    <table class="table table-bordered mt-5">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nombre</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>

  <tbody>
  @foreach($roles as $role)
    <tr>
      <th>{{$role->id}}</th>
      <td>{{$role->name}}</td>
      <td >
        <a href="{{route('admin.roles.edit',['role'=>$role->id])}}" type="button" class="btn btn-primary">EDITAR</a>
       
        <form  class="form-delete" action="{{route('admin.roles.destroy',['role'=> $role->id] )}}"   method="POST">
          @csrf
          @method('delete')
        <button type="submit" class="btn btn-danger">BORRAR</button>
        </form>

      </td>
    </tr>
    @endforeach

  </tbody>
  <tbody>


  </tbody>
</table>

@stop

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<link href="{{asset('/css/styles.css')}}" rel="stylesheet">

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
  $('.form-delete').submit(function(e){
    e.preventDefault();

      Swal.fire({
        title: '¿Estás seguro?',
        text: "¡El registro se eliminará permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '¡Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        this.submit();
      }
    })
  });

</script>
@stop