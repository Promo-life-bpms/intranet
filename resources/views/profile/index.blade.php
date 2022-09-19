@extends('layouts.app')
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@section('content')

    <div class="card-body">

        <div class="banner">
            <div class="d-flex justify-content-start user-info" style="width: 100%;">

                @foreach ($user as $usr)
                    <div class="container-image  rounded-circle">
                        @if ($usr->image == null)
                            <div class="image">
                                <img class="profile-picture"
                                    src="https://img.freepik.com/free-vector/man-shows-gesture-great-idea_10045-637.jpg?size=338&ext=jpg"
                                    alt="">
                            </div>
                        @else
                            <div class="image">
                                <img class="profile-picture" src="{{ asset('') . $usr->image }}" alt="">
                            </div>
                        @endif
                        <div class="change-image" style="z-index: 10;">
                            <button type="button" class="btnCreate" data-bs-toggle="modal" data-bs-target="#modalImage">
                                <i class="fa fa-camera " aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <div class="container-text  text">
                    <h3>
                        <td>{{ $usr->name . ' ' . $usr->lastname }}</td>
                    </h3>
                    <h5>{{ $usr->employee->position->name }} </h5>
                </div>

            </div>
        </div>




        <div class="hidden-text" style="display: none">
            <div class="separador" style="margin-top:120px "></div>
            <h3>
                <td>{{ $usr->name . ' ' . $usr->lastname }}</td>
            </h3>
            <h5>{{ $usr->employee->position->name }} </h5>
            <br>
        </div>
        <div class="separador" style="margin-top:120px "></div>


        <div class="row">
            <div class="col-md-4">
                <div class="card border-light p-4" style="background-color: #F4F4F4">
                    @foreach ($user as $usr)
                        <div class="d-flex align-items-start">
                            <span>
                                <i class="fa fa-building " aria-hidden="true"></i>
                            </span>

                            @if (!empty($usr->employee->position->department->name))
                                <p style="padding-left: 10px; max-width:90%;">
                                    {{ $usr->employee->position->department->name }}</p>
                            @else
                                <p>Sin departamento especificado</p>
                            @endif
                        </div>

                        <div class="d-flex align-items-start">
                            <span>
                                <i class="fa fa-briefcase" aria-hidden="true"></i>
                            </span>

                            @if (!empty($usr->employee->position->name))
                                <p style="padding-left: 10px; max-width:90%;">{{ $usr->employee->position->name }} </p>
                            @else
                                <p>Sin puesto especificado</p>
                            @endif
                        </div>

                        <div class="d-flex align-items-start">

                            <span>
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </span>

                            @if (!empty($usr->email))
                                <p style="padding-left: 10px; ">{{ $usr->email }} </p>
                            @else
                                <p>Sin correo especificado</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-8 border-light p-3" style="background-color: #F4F4F4">

                <div class="col-md-12 p-0">

                    @livewire('publications-profile-component',['user'=>$usr])
                </div>


            </div>

        </div>




    </div>

    <div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalImageLabel">Seleccione la imagen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'profile.change', 'enctype' => 'multipart/form-data']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="mb-2 form-group">
                                {!! Form::file('image', ['class' => 'form-control']) !!}
                            </div>
                            @error('image')
                                <small>
                                    <font color="red"> *Este campo es requerido* </font>
                                </small>
                                <br>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    {!! Form::submit('Aceptar', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop


@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <style>
        .input-group-text {
            background-color: #1A346B;
            color: #ffffff;
        }


        .banner {
            width: 100%;
            height: 250px;
            background-image: linear-gradient(rgba(76, 216, 255, 0.8), rgba(30, 108, 217, 0.8)),
                url('http://www.trademarket.com.mx/assets/imgs/quienes.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            justify-content: center;
            background-position: center center;
            border-radius: 10px;
        }

        .container-image {
            width: 200px;
            height: 200px;
            background: #ffffff;
            margin: 120px 40px 0 40px;
            overflow: hidden;

        }

        .image {
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .change-image {
            width: 100%;
            height: 60px;
            margin-top: -22%;
            overflow: hidden;
            z-index: 20;
        }

        .btnCreate {
            opacity: 0.7;
        }

        .profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }


        .container-text {
            margin-top: 160px;

        }

        .container-text>* {
            color: #ffffff;
        }

        .box {
            box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px;
            border-radius: 10px;

        }

        @media screen and (max-width: 768px) {

            #sidebar~#main {
                padding: 0;
            }

            .user-info {
                margin-bottom: 200px;
                align-items: center;
                flex-direction: column;
            }

            .container-image {

                margin-top: 120px;
                position: absolute;
            }

            .container-text {
                margin: 340px 0 0 0;
            }

            .container-text>* {
                color: #032A3D;
                text-align: center;
                margin: 5px 0 0 0;
            }

            .col-md-4 {
                margin-top: 80px;
            }

        }
    </style>
@stop
