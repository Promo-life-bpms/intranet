@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Accesos</h3>
            {{-- <a href="{{ route('access.create') }} " type="button" class="btn btn-success">Agregar</a> --}}
        </div>
    </div>

    <div class="card-body m-2">
        <div class="row">
            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/course.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Cursos</h5>
                        <a href="{{ $routeCourses }}" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/task-clipboard.png') }}"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Task Manager</h5>
                        <a href="{{ $routeTaskManager }}" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="https://www.odoo.com/web/image/website/1/social_default_image?unique=ffdde0a"
                        style="width: 100%; height:120px;  object-fit: contain;" class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">ODOO</h5>
                        <a href="https://promolife.vde-suite.com:8030/web/login" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/evaluacion.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Evaluación 360</h5>
                        <a href="https://evaluacion.promolife.lat/login" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/nom.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">NOM 035</h5>
                        <a href="https://plataforma.nom-035.net/" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/cotizador.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">CATALOGO</h5>
                        <a href="{{ $routeCatalogo }}" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/tickets.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Sistema de Diseño</h5>
                        <a href="https://tdesign.promolife.lat" style="width: 100%" target="_blank"
                            class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 col-xl-3">
                <div class="card text-dark bg-light rounded shadow p-4">
                    <img src="{{ asset('/img/powerbi.png') }}" style="width: 100%; height:120px;  object-fit: contain;"
                        class="card-img-top" alt="imagen">
                    <div class="card-body" style="padding-top:0; padding-bottom:0">
                        <h5 class="card-title text-center">Power BI</h5>
                        <a href="https://app.powerbi.com/singleSignOn?ru=https:%2f%2fapp.powerbi.com%2f%3fnoSignUpCheck%3d1"
                            style="width: 100%" target="_blank" class="btn btn-primary with">ABRIR</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="container d-flex justify-content-around">

                @foreach ($access as $acc)
                    <div class="col-md-3">
                        <div class="card text-dark bg-light ">
                            <div class="card-header text-dark bg-light p-2">
                                <h4 class="text-center"> {{ $acc->title }}</h4>
                            </div>
                            <div class="card-body">
                                <img style="width: 100%; max-height:120px;  object-fit: contain;"
                                    src="{{ $acc->image }}">

                                <a style="width: 100%; justify-content:center;" target="_blank"
                                    href="{{ $acc->link }}" type="button"
                                    class="btn btn-primary d-flex align-items-center mt-2 mb-2">INGRESAR</a>

                                <div class="d-flex justify-content-center">
                                    <a style="width: 80px" href="{{ route('access.edit', ['acc' => $acc->id]) }}"
                                        type="button" class="btn btn-outline-success m-2">VER</a>
                                    <form class="form-delete"
                                        action="{{ route('access.delete', ['acc' => $acc->id]) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button style="width: 90px" type="submit"
                                            class="btn btn-outline-danger m-2">BORRAR</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                @endforeach
            </div>

        </div>

    </div>
@stop

@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.form-delete').submit(function(e) {
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
