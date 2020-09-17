<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Blab</title>

        <script src="/js/app.js" defer></script>

        <link href="/css/app.css" rel="stylesheet">

        <script src="https://kit.fontawesome.com/584495cc88.js"></script>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <meta property="og:title" content="Blab" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="We deliver always available rooms for office banter, meetings, or quick questions." />
        <meta property="og:url" content="https://blab.to" />
        <meta property="og:image" content="https://blab.to/img/og-hero.png" />

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
            h4 {
                font-weight:800
            }
            td {
                font-size:1rem;
                font-weight:600
            }
            td .lead {
                font-size:1.05rem;
                margin-top:.5rem!important;
                margin-bottom:.5rem!important;
            }
            .pricing-table-feature  {
                font-weight:500!important;
                text-align:center;
            }
        </style>

    </head>
    <body class="px-md-5 px-2">
        <div class="container">
            <div>
                <nav class="navbar navbar-expand-lg navbar-light text-dark">
                    <a class="navbar-brand" href="/" style="font-weight:900;font-size:2.5rem;">blab</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <!--
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="#features"><i class="fas fa-flag"></i> Features</a>
                            </li>   
                            -->
      <!--                       <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="#pricing"><i class="fas fa-tags"></i> Pricing</a>
                            </li>    -->
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="/login">Log In</a>
                            </li>   
                            <li class="nav-item">
                                <a class="btn btn-success shadow text-dark my-3 font-weight-bold" href="/get_started" rel="nofollow">Start Now For Free</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>    

        <main class="py-4">
            @yield('content')
        </main>
    </body>
</html>