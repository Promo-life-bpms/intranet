@extends('layouts.app')

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

                    @if (count($publications) <= 0)
                        <p>No hay Publicaciones</p>
                    @else
                        @foreach ($publications as $publication)
                            <div class="m-0 p-0" style="border-radius:20px;">
                                <div class="card p-3 box">
                                    <div class="d-flex head">
                                        <div class="imagen px-1">
                                            <div class="avatar avatar-xl">
                                                <div class="card-photo" style="width: 40px; height:40px;">
                                                    @if (auth()->user()->image == null)
                                                        <a style="color: inherit;"
                                                            href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <p
                                                                class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                <span>{{ substr($publication->user->name, 0, 1) . substr($publication->user->lastname, 0, 1) }}</span>
                                                            </p>
                                                        </a>
                                                    @else
                                                        <a style="color: inherit;"
                                                            href="{{ route('profile.view', ['prof' => $publication->user_id]) }}">
                                                            <img style="width: 100%; height:100%; object-fit: cover;"
                                                                src="{{ asset($publication->user->image) }}">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nombre">
                                            <p class="m-0 " style="font-weight: bold">
                                                {{ $publication->user->name . ' ' . $publication->user->lastname }}
                                            </p>
                                            <p class="m-0">
                                                {{ $publication->created_at->diffForHumans() }} </p>
                                        </div>
                                    </div>
                                    <p class="mt-4 ">
                                        {{ $publication->content_publication }} </p>
                                    @if (count($publication->files) > 0)
                                        <div class="row" style="overflow:hidden;">
                                            @if (count($publication->files) == 1)
                                                @foreach ($publication->files as $item)
                                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                                        data-lightbox="photos.{{ $publication->id }}"
                                                        style="height: 600px;">
                                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                                            class="rounded shadow-sm"
                                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                                    </a>
                                                @endforeach
                                            @elseif (count($publication->files) == 2)
                                                @foreach ($publication->files as $item)
                                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                                        data-lightbox="photos.{{ $publication->id }}"
                                                        style="height: 300px;" class="col-md-6">
                                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                                            class="rounded shadow-sm"
                                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                                    </a>
                                                @endforeach
                                            @elseif (count($publication->files) == 3)
                                                @foreach ($publication->files as $item)
                                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                                        data-lightbox="photos.{{ $publication->id }}"
                                                        style="height: 200px;" class="col-md-4">
                                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                                            class="rounded shadow-sm"
                                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                                    </a>
                                                @endforeach
                                            @elseif (count($publication->files) > 3)
                                                @foreach ($publication->files as $item)
                                                    <a href="{{ asset('/storage/posts/') . '/' . $item->resource }}"
                                                        data-lightbox="photos.{{ $publication->id }}"
                                                        style="height: {{ $loop->iteration > 4 ? '0' : '200' }}px;"
                                                        class="col-md-3">
                                                        <img style="width:100%; height: 100%; object-fit: cover; background-position: center center;"
                                                            class="rounded shadow-sm {{ $loop->iteration > 3 && count($publication->files) > 4 ? 'd-none' : '' }}"
                                                            src="{{ asset('/storage/posts/') . '/' . $item->resource }}">
                                                        @if ($loop->iteration == 4 && count($publication->files) > 4)
                                                            <div class="w-100 h-100 d-flex justify-content-center align-items-center mas-fotos"
                                                                style="background-color: #dcdcdc;">
                                                                <p style="font-size: 15px; font-weight: bold">Ver mas</p>
                                                            </div>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                    <hr>
                                    <div class="d-flex justify-content">
                                        <div id="boton">
                                            <like-button style="margin-top: -24px; overflow:hidden;"
                                                publication-id="{{ $publication->id }}"
                                                like="{{ auth()->user()->meGusta->contains($publication->id) }}"
                                                likes="{{ $publication->like->count() }}">
                                            </like-button>
                                        </div>

                                    </div>

                                    <a style="font-size:18px; color:#000000;" data-bs-toggle="collapse"
                                        href="#collapse{{ $publication->id }}" role="button"
                                        aria-controls="collapse{{ $publication->id }}">
                                        @php
                                            $contador = 0;
                                            foreach ($publication->comments as $comment) {
                                                $contador = $contador + 1;
                                            }
                                        @endphp
                                        Ver comentarios (<?= $contador ?>)
                                    </a>

                                    <div class="collapse mt-4" id="collapse{{ $publication->id }}">
                                        @foreach ($publication->comments as $comment)
                                            <div class="nombre d-flex flex-row">
                                                <div class="com_image">
                                                    <div class="card-photo rounded-circle "
                                                        style="width: 40px; height:40px;">
                                                        @if ($comment->user->image == null)
                                                            <a style="color: inherit;"
                                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                                <p
                                                                    class="rounded-circle border border-primary m-0 d-flex justify-content-center align-items-center width-icon">
                                                                    <span>{{ substr(auth()->user()->name, 0, 1) . substr(auth()->user()->lastname, 0, 1) }}</span>
                                                                </p>
                                                            </a>
                                                        @else
                                                            <a style="color: inherit;"
                                                                href="{{ route('profile.view', ['prof' => $comment->user_id]) }}">
                                                                <img style="width: 100%; height:100%; object-fit: cover;"
                                                                    src="{{ $comment->user->image }}">
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="com_content">
                                                    <h6 class="ml-4">
                                                        {{ $comment->user->name . ' ' . $comment->user->lastname }}
                                                    </h6>
                                                    <p class="ml-4 public-text">
                                                        {{ $comment->content }}
                                                    </p>
                                                </div>
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="com">
                                        <div class="card card-body" style="padding-bottom: 0;">
                                            <form method="POST" action="{{ route('comment') }}" class="comment">
                                                @csrf
                                                <input name="id_publication" id="id_publication" type="hidden"
                                                    value="{{ $publication->id }}">
                                                <div class="form-group row ">
                                                    <div class="col-md-12 align-content-center m-0 p-0">
                                                        <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror"
                                                            placeholder="Escribe tu comentario..."></textarea>
                                                        @error('content')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-0">
                                                    <div class="d-flex justify-content-end pr-0">
                                                        <button type="submit" class="boton">
                                                            Comentar
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


            </div>

        </div>

    </div>



@endsection


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
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
@endsection
