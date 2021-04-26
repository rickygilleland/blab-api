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
            h1 {
                font-weight: 600;
                font-size: 7.5rem;
                line-height: .91667;

                @media (max-width: 1000px) {
                    font-size: 3rem;
                }
            }
            h2 {
                font-weight: 600;
                font-size: 4rem;
                line-height: .91667;

                @media (max-width: 1000px) {
                    font-size: 2.5rem;
                }
            }
            .sub-heading {
                font-weight: 600;
                font-size: 32px;
                line-height: 1.09375;
                margin: 0 auto;
            }
            .sub-grey {
                color: #bebec5;
            }
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
            .container-dark {
                background-color: #212529;
                color: white
            }
            .container-light {
                background-color: #fff;
                color: #212529;
            }
            .container-dark, .container-light {
                min-height: 450px;
            }
            .hero {
                max-width: 500px;
                border-radius: 1.2rem;
                opacity: .95;
                margin: 2rem 0;
            }
            .hero-gradient .fa-arrow-alt-circle-down {
                margin-top: 1.25rem;
                font-size: 3rem;
            }
            .hero-gradient {
                background: radial-gradient(circle, #5ADFFF, #42C3FF, #1ca3ec, #2389da);
                background-size: 600% 600%;

                -webkit-animation: HeroGradient 25s ease infinite;
                -moz-animation: HeroGradient 25s ease infinite;
                animation: HeroGradient 25s ease infinite;
                color: #fff;
                padding-bottom: 4.5rem;
            }

            @-webkit-keyframes HeroGradient {
                0%{background-position:0% 50%}
                50%{background-position:100% 51%}
                100%{background-position:0% 50%}
            }
            @-moz-keyframes HeroGradient {
                0%{background-position:0% 50%}
                50%{background-position:100% 51%}
                100%{background-position:0% 50%}
            }
            @keyframes HeroGradient {
                0%{background-position:0% 50%}
                50%{background-position:100% 51%}
                100%{background-position:0% 50%}
            }


        </style>

    </head>
    <body class="container-dark">

        <div class="hero-gradient">

            <div class="container-fluid">
                <div>
                    <nav class="navbar navbar-expand-lg navbar-light text-dark">


                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#fff!important" href="/login">Log In</a>
                                </li>
                                <li class="nav-item">
                                <center><a class="btn shadow btn-lg text-light mt-3 mb-5 font-weight-bold hero-gradient" style="padding-bottom:0.5rem" href="/invite" rel="nofollow">Request Access</a></center>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <div class="container-fluid">

                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center mt-md-5">The Water Cooler is <br>Remote Now Too</h1>
                        <center><img src="/img/og-hero.png" class="hero img-fluid" /></center>

                        <p class="text-center sub-heading my-4">Simple, secure, and enhanced by machine learning.<br />Built for the next generation of work.</p>
                        <center><i class="fas fa-arrow-alt-circle-down"></i></center>

                    </div>
                </div>


            </div>
        </div>

        <div class="container-fluid container-light">
            <div class="row" style="padding-top:5.5rem;">
                <div class="col-12">
                    <h2 class="text-center mt-md-5">Bring serendipity back to your team.</h2>
                    <p class="text-center sub-heading sub-grey my-4">Blab rooms are always available for more natural, spontaneous conversations.<br />Rooms are voice only by default for when you aren't quite camera ready.</p>
                    <div style="overflow:hidden;max-height:500px;padding:2em;margin-bottom:8rem;">
                        <center><video src="/img/main-demo.mp4" class="img img-fluid rounded shadow mt-4 mt-md-0 w-100" style="max-width:750px" autoplay playsinline muted loop /></center>
                    </div>
                </div>
            </div>
        </div>



        <div class="container-fluid container-dark">

            <div class="row">
                <div class="col-12">
                    <h2 class="text-center mt-md-5"><i class="fas fa-crop-alt"></i></h2>
                    <h2 class="text-center mt-md-5">AI powered auto zoom.</h2>
                    <p class="text-center sub-heading sub-grey my-4">Never worry about your background again.<br/>We use AI to keep the focus on you, not what's going on around you.</p>
                </div>
            </div>

            <div style="overflow:hidden;max-height:400px;padding:2em">
                <center><video src="/img/main-demo-2.mp4" class="img img-fluid rounded shadow mt-4 mt-md-0 w-100" autoplay playsinline muted loop /></center>
            </div>

        </div>

        <div class="container-fluid container-light">
            <div class="row" style="padding-top:5.5rem;">
                <div class="col-12">
                    <h2 class="text-center"><i class="fas fa-headset"></i></h2>
                    <h2 class="text-center mt-md-5">Say goodbye to background noise.</h2>
                    <p class="text-center sub-heading sub-grey my-4 mb-5">Blab uses a neural network to isolate your voice and strip out everything else happening around you.<br />It runs entirely on your computer for a private and secure experience.</p>
                    <p class="text-center sub-heading" style="margin-bottom:5.25rem">Coming soon.</p>
                </div>
            </div>
        </div>

        <div class="container-fluid container-dark">
            <div class="row" style="padding:5.5rem 0;">
                <div class="col-4">
                    <h2 class="text-center mt-md-5 mb-4"><i class="fas fa-lock"></i></h2>
                    <p class="text-center sub-heading sub-grey mb-3">Encrypted in transit</p>
                    <p class="text-center sub-heading sub-grey mb-3">User data encrypted at rest</p>
                    <p class="text-center sub-heading sub-grey mb-3">Rooms can only be joined by users within your organization</p>
                </div>
                <div class="col-4">
                    <h2 class="text-center mt-md-5 mb-4"><i class="fas fa-server"></i></h2>
                    <p class="text-center sub-heading sub-grey mb-3">Custom voice/video infrastructure</p>
                    <p class="text-center sub-heading sub-grey mb-3">Streams are optimized for limited bandwidth</p>
                    <p class="text-center sub-heading sub-grey mb-3">Powered by WebRTC and open source software</p>
                </div>
                <div class="col-4">
                    <h2 class="text-center mt-md-5 mb-4"><i class="fas fa-user-secret"></i></h2>
                    <p class="text-center sub-heading sub-grey mb-3">All AI models run locally with no cloud processing</p>
                    <p class="text-center sub-heading sub-grey mb-3">Voice/video data is never processed by 3rd parties</p>
                </div>
            </div>
        </div>

        <div class="container-fluid container-light">
            <div class="row" style="padding-top:5.5rem;">
                <div class="col-12">
                    <h2 class="text-center mt-md-5">Completely free for now.</h2>
                    <p class="text-center sub-heading sub-grey my-4">Paid plans launching later this year.<br />A free tier will be available forever.</p>
                </div>
            </div>
        </div>

        <div class="container-fluid container-dark">
            <div style="max-width:1400px" class="mx-auto">
                <h2 class="text-center" style="padding-top:6rem">Get on the list.</h2>
                <p class="text-center sub-heading sub-grey mb-4 mt-3">Request access to Blab and we'll get you onboarded for free soon.</p>
                <center><a class="btn shadow btn-lg text-light mt-3 mb-5 font-weight-bold hero-gradient" style="padding:1.2rem 1.75rem;font-size:1.25rem" href="/invite" rel="nofollow">Request Access</a></center>
                <p class="text-center font-weight-bold" style="font-size:1.1rem;margin-bottom:5rem">Questions? Drop us a line at hello (at) blab.to</p>

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
