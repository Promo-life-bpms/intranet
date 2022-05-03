<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    @yield('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>

<body>
    <div id="app">

        @include('layouts.components.sidebar')
        <div class="col-12 order-md-1 order-last d-flex justify-content-end align-items-center">
            @yield('title')
            <div class="d-flex align-items-center">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <svg class="bi bell" fill="currentColor">
                        <use
                            xlink:href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.svg#bell-fill') }}" />
                    </svg>
                    <span class="badge-number position-absolute translate-middle badge rounded-pill bg-danger"
                        style="font-size: 0.7rem">
                        {{ count(auth()->user()->unreadNotifications) }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark" style="max-height: 500px; overflow-y: scroll;"
                    aria-labelledby="navbarDropdownMenuLink">
                    @include('layouts.components.notifies')
                </ul>

            </div>
        </div>
        <div id="main">
            @if (request()->is('home'))
                @include('layouts.components.logos')
            @endif
            <div class="px-3">
                {{-- Menu Hamburguesa --}}
                <header class="mb-3 d-xl-none">
                    <a href="#" class="burger-btn d-block">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>
                <div class="page-heading ">
                    <div id="appVue">
                        <div class="page-title">
                            <div class="row">
                                <div class="col-12">
                                    @yield('title')
                                </div>
                            </div>
                        </div>
                        <section class="section">
                            @yield('dashboard')
                            <div class="card">
                                @yield('content')
                            </div>
                        </section>
                    </div>
                </div>
                @include('layouts.components.footer')
                <chat-component :authId="{{ auth()->user()->id }}"></chat-component>
                <notify :auth-id={{ auth()->user()->id }}></notify>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/momentjs/moment.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/mazer.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>


    @yield('scripts')
    <script src="https://use.fontawesome.com/84b288f169.js"></script>
</body>

</html>
