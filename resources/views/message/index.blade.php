<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- OG tags -->
    <meta property="og:title" content="Blab" />
    @if (strpos($attachment->mime_type, "audio") !== false)
        <meta property="og:audio" content="{{ $attachment->temporary_url }}" />
        <meta property="og:description" content="{{ $attachment->user->first_name }} shared a voice clip on Blab." />
        <meta name="twitter:title" content="View {{ $attachment->user->first_name }}'s Audio Blab">
        <meta property="og:audio:type" content="audio/wav" />
    @else
        <meta property="og:video" content="{{ $attachment->temporary_url }}" />
        <meta property="og:video:type" content="video/mp4" />
        <meta property="og:video:width" content="466" />
        <meta property="og:video:height" content="350" />
        <meta property="og:video:secure_url" content="{{ $attachment->temporary_url }}" />
        <meta property="og:description" content="{{ $attachment->user->first_name }} shared a video on Blab." />
        <meta property="og:image" content="https://blab.sfo2.digitaloceanspaces.com/{{ $attachment->thumbnail_temporary_url }}" />
        <meta property="og:image:secure_url" content="https://blab.sfo2.digitaloceanspaces.com/{{ $attachment->thumbnail_temporary_url }}" />
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="466" />
        <meta property="og:image:height" content="350" />
        <meta name="twitter:title" content="View {{ $attachment->user->first_name }}'s Video Blab">
    @endif
    <meta property="og:url" content="https://blab.to/b/{{ $organization_slug }}/{{ $blab_slug }}" />
    <meta name="twitter:site" content="@tryblab">

    <title>{{ config('app.name', 'Blab') }}</title>

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

    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/7015051.js"></script>
    <!-- End of HubSpot Embed Code -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3RVXY9EZ49"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-3RVXY9EZ49');
    </script>

    <script>
      !function(t,e){var o,n,p,r;e.__SV||(window.posthog=e,e._i=[],e.init=function(i,s,a){function g(t,e){var o=e.split(".");2==o.length&&(t=t[o[0]],e=o[1]),t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}}(p=t.createElement("script")).type="text/javascript",p.async=!0,p.src=s.api_host+"/static/array.js",(r=t.getElementsByTagName("script")[0]).parentNode.insertBefore(p,r);var u=e;for(void 0!==a?u=e[a]=[]:a="posthog",u.people=u.people||[],u.toString=function(t){var e="posthog";return"posthog"!==a&&(e+="."+a),t||(e+=" (stub)"),e},u.people.toString=function(){return u.toString(1)+".people (stub)"},o="capture identify alias people.set people.set_once set_config register register_once unregister opt_out_capturing has_opted_out_capturing opt_in_capturing reset".split(" "),n=0;n<o.length;n++)g(u,o[n]);e._i.push([i,s,a])},e.__SV=1)}(document,window.posthog||[]);
      posthog.init('64tUVTgJhFVIV7BADDLYHN-zG2Ja1yqzOI_SE8Pytc4', {api_host: 'https://analytics.blab.to'})
    </script>


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
        h1 a {
            font-weight: 900!important;
            color:white!important;
        }
        video, audio {
            outline: none;
        }
        video {
            border-radius: 25px;
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        }
    </style>
</head>
<body>
    <div id="app" style="background-color:#0076ff;" class="vh-100 onboarding">
        <h1 class="text-center pt-5 text-light"><a href="/">blab</a></h1>
       
        <main class="py-4">
            <div class="container">
                <div class="row">
                
                    <div class="col-md-8 mx-auto">
                        
                        <div class="card shadow">
                            
                            <div class="card-body p-md-5">

                                <div class="row">
                                    <div class="col-12 col-lg-6 d-flex flex-row align-items-center">
                                        <div>
                                            <img src="{{ $attachment->user->avatar_url }}" class="img rounded" style="height:75px;width:auto" />
                                        </div>
                                        <div class="ml-3">
                                            <p style="font-size:1.05rem;font-weight:600">Shared by {{ $attachment->user->first_name }}<br><small class="text-muted">{{ $attachment->created_at->diffForHumans() }} </small></p>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="row">

                                    <div class="col-12 pt-5 pb-3 p-md-5">
                                        <center>
                                            @if (strpos($attachment->mime_type, "audio") === false)
                                                <video width="100%" controls src="{{ $attachment->temporary_url }}" />
                                            @else
                                                <audio width="100%" controls src="{{ $attachment->temporary_url }}" controlsList="nodownload" />
                                            @endif
                                        </center>
                                    </div>

                            </div>

                        </div>
                        
                    </div>
                </div>
                
            </div>
        </main>
    </div>

</body>
</html>