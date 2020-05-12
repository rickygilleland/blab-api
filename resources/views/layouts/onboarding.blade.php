<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Water Cooler') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://kit.fontawesome.com/584495cc88.js"></script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.25.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.28/moment-timezone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.28/moment-timezone-with-data.min.js"></script>-->


    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <style>
        h1, .h1 {
            font-weight:900
        }
        h2, .h2 {
            font-weight:800
        }
        .btn {
            font-weight:600
        }
    </style>
</head>
<body>
    <div id="app">

   
        <center><img src="/img/water_cooler.png" class="img img-fluid mt-4 mb-3" style="height:75px"></center>
       
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script type="application/javascript">
        document.getElementById('continue-btn').addEventListener('click', async (e) => {
            document.getElementById('continue-btn').innerHTML = '<i class="fas fa-circle-notch fa-spin text-light mr-2" style="font-size:.9rem"></i>Signing You Up...';
        });
    </script>
</body>
</html>
