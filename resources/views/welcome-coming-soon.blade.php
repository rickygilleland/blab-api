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
        <meta property="og:description" content="AI enhanced voice first chat for the next generation of WFH." />
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
            .card {
                border-radius: 25px;
            }
        </style>

    </head>
    <body class="px-md-5 px-2" style="background-color: #212529;color: white">

        <div class="container-fluid">
            <div>
                <nav class="navbar navbar-expand-lg navbar-light text-dark">
                    <a class="navbar-brand" href="#" style="font-weight:900;font-size:2.5rem;">blab</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#fff!important" href="/login">Log In</a>
                            </li>
                            <li class="nav-item">
                                <a class="btn btn-success shadow text-dark my-3 font-weight-bold" href="/get_started" rel="nofollow">Start Now For Free</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mt-md-5" style="font-weight:700;font-size:6rem">Not Your Father's Video Chat</h1>
                    <p class="text-center mt-4" style="font-weight:500;font-size:2.2rem;">Face to face conversations that stay out of the way.</p>
                    <p class="text-center mt-3" style="font-weight:400;font-size:1.6rem;">Simple, secure, and enhanced by machine learning. Built for the next generation of work.<br/><span style="font-size:1.3rem">Available on MacOS and Windows.</span></p>
                    <center><a class="btn btn-success shadow btn-lg text-dark my-2 font-weight-bold" href="/get_started" rel="nofollow">Start Now For Free</a></center>
                </div>
            </div>

            <div style="overflow:hidden;max-height:400px;padding:2em">
                <center><video src="/img/main-demo-2.mp4" class="img img-fluid rounded shadow mt-4 mt-md-0 w-100" autoplay playsinline muted loop /></center>
            </div>

        </div>


        <div class="container-fluid">
            <div style="max-width:1400px" class="mx-auto">
                <p class="text-center font-weight-bold" style="font-size:1.1rem;margin-top:7rem;margin-bottom:5rem">Questions? Drop us a line at hello (at) blab.to</p>

            </div>
        </div>


        <!-- Twitter universal website tag code -->
        <script>
        !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
        },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
        a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
        // Insert Twitter Pixel ID and Standard Event data below
        twq('init','o3sy2');
        twq('track','PageView');
        </script>
        <!-- End Twitter universal website tag code -->


    </body>
</html>
