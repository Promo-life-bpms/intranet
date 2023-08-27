<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\vendors\toastify\toastify.css') }}">
    @yield('styles')

    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app6.css') }}">
</head>

<body>
    <div class="container" style="height: 100vh">
        <div class="d-flex justify-content-center h-100 align-items-center">
            <div style="min-width: 35%">
                <div class="contenedor-logo">
                    <ul class="logos" style="padding-left: 10px;">
                        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/bhtrade.png') }}"
                                    alt="bhtrade"></a> </li>
                        <li><a href="#"><img style="max-width: 80px;" src="{{ asset('/img/promolife.png') }}"
                                    alt="promolife"></a>
                        </li>
                        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/promodreams.png') }}"
                                    alt="promodreams"></a>
                        </li>
                        <li><a href="#"><img style="max-width: 50px;" src="{{ asset('/img/trademarket.png') }}"
                                    alt="trademarket"></a>
                        </li>
                    </ul>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app6.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>
    @yield('scripts')
    <script src="{{ asset('assets\vendors\toastify\toastify.js') }}"></script>
    <script src="{{ asset('assets/js/mazer.js') }}"></script>
    <script src="https://use.fontawesome.com/84b288f169.js"></script>
</body>

</html>
