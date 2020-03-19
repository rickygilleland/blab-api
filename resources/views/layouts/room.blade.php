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
    <script src="//media.twiliocdn.com/sdk/js/video/releases/2.0.0/twilio-video.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <style>
        .col {
            flex-grow: 0 !important;
        }
        .video-local {
            height: 125px;
            position:absolute;
            bottom:0;
            right:0;
            padding:5px;
        }
    </style>
</head>
<body class="room" style="background-color:#4F4581">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm" id="roomNav">
            <div class="container-fluid">
                <img src="/img/water_cooler.png" class="img img-fluid" style="height:45px">
                @if ($user->teams->count() > 1)
                    <p style="color:white" class="ml-3 pt-2"><a href="/o/{{ $organization->slug }}">{{$organization->slug }}</a> / <a href="/o/{{ $organization->slug }}/{{ $team->slug }}">{{ $team->slug }}</a> / {{ $room->slug }}</p>
                @else
                    <p style="color:white" class="ml-3 pt-2">{{ $team->slug }} / {{ $room->slug }}</p>
                @endif
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link d-none" href="#settings" style="color:white!important;font-size:1.3rem"><i class="fas fa-cog"></i></a>
                        </li>
                    </ul>

                    
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <script type="application/javascript">

        var is_room = true;

        var activeRoom;
        var previewTracks;

        var identity = "{{ $user->access_token }}";
        var roomName = "{{ $room->twilio_room_name }}";

        //video code

        const Video = Twilio.Video;
   
    </script>
</body>
</html>
