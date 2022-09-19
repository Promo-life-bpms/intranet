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
    <link rel="stylesheet" href="{{ asset('css/app4.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    @livewireStyles()
</head>

<body>
    @include('layouts.components.sidebar')
    @yield('title')
    <div id="main" class="mt-0 pt-0 px-1">
        @include('layouts.components.logos')
        <div class="px-2">
            <section class="section">
                @yield('dashboard')
                <div class="card">
                    @yield('content')
                </div>
            </section>
            @include('layouts.components.footer')
        </div>
    </div>

    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/momentjs/moment.js') }}"></script>
    <script src="{{ asset('js/app5.js') }}"></script>
    <script src="{{ asset('assets/js/mazer.js') }}"></script>


    @yield('scripts')
    <script src="https://use.fontawesome.com/84b288f169.js"></script>

    @livewireScripts()
</body>

</html>
