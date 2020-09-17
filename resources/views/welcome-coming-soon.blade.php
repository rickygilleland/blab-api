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
                    <a class="navbar-brand" href="#" style="font-weight:900;font-size:1.75rem;"><img src="img/blab-app-icon.png" class="img img-fluid" style="height:60px"></a>

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

        <div class="container">

            <div class="row my-md-5">
                <div class="col-12">
                    <h1 class="text-center" style="font-weight:900;font-size:5rem">Let Your Team Hear You</h1>
                    <p class="text-center mt-4" style="font-weight:600;font-size:1.13rem;">The convenience of face to face conversation without the hassle of scheduling meetings.<br/>Send a voice or video message and let your teammates get back to you, or pop into a room to hang out live.</p>
                    <center><a class="btn btn-success shadow btn-lg text-dark my-2 font-weight-bold" href="/get_started" rel="nofollow">Start Now For Free</a></center>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <center><img src="/img/main-hero-demo.gif" class="img img-fluid rounded shadow mt-4 mt-md-0 w-100" style="max-width:975px"></center>
                </div>
            </div>
        
        </div>

        <!--
        <div class="container" style="background-color:#fff;color:black" id="features">
            <div style="max-width:1400px" class="mx-auto py-5">

                <div class="row mb-5 clearfix">
                    <div class="col-md-6">
                        <h3 class="h2" style="font-weight:800;margin-top:2.5rem">Say Goodbye to Live Meetings</h3>
                        <p class="lead" style="font-weight:500">Let you and your co-workers take back their time.</p>
                        <p class="lead" style="font-weight:500">Great for general office chit-chat, recurring meetings, or 1:1 conversations with your co-workers.</p>      
                        <p class="lead" style="font-weight:500">Whenever a member of your team is in a different timezone than yours, we automatically overlay their local time whenever they're in a room.</p>  
                    </div>
                    <div class="col-md-6">
                        <img src="/img/overview-screenshot.png" style="max-height:650px" class="img img-fluid rounded float-right" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container" style="background-color:#0076ff;color:white;border-radius:50px">
            <div style="max-width:1400px" class="mx-auto py-5">
                <div class="row mb-5 clearfix">
                    <div class="col-md-6">
                        <h3 class="h2" style="font-weight:800;margin-top:3.25rem">Secure HD Video and Audio</h3>
                        <p class="lead" style="font-weight:500">Create rooms that are available to the entire team, or restricted to a few select team members.</p>
                        <p class="lead" style="font-weight:500">Great for general office chit-chat, recurring meetings, or 1:1 conversations with your co-workers.</p>      
                        <p class="lead" style="font-weight:500">Whenever a member of your team is in a different timezone than yours, we automatically overlay their local time whenever they're in a room.</p>  
                        <p class="lead" style="font-weight:500">Our video and voice chat is powered by WebRTC for low-latency connections and all streams are encrypted while in transit.</p>
                        <p class="lead" style="font-weight:500">All rooms are voice only by default, putting the conversation at center stage and reducing distractions and embarrassing moments.</p> 
                        <p class="small">*Video quality and resolution may be reduced at times depending on network conditions.</p>             
                    </div>
                    <div class="col-md-6">
                        <img src="/img/video-audio-containers-screenshot.png" style="max-height:400px" class="img img-fluid rounded shadow float-right" />
                    </div>
                </div>

            </div>
        </div>
        -->

        <div class="container" style="background-color:#fff;color:black" id="pricing">
            <div style="max-width:1400px" class="mx-auto py-5">
               <h3 class="h1 text-center mt-5 mb-3" style="font-weight:800">Blab Pricing</h3>
                <p class="text-center pt-0 mb-5" style="font-size:1.1rem">Start off with a free account and upgrade when you outgrow it. All paid plans come with a 7 day free trial.</p>
                

                <div class="row">
                    <div class="col d-flex align-items-stretch">
                        <div class="card card-body shadow-sm" style="border-top: 5px solid #121422">
                            <h3 class="mb-0 pb-0" style="font-weight:700">Basic</h3>
                            <p class="my-5" style="font-weight:700;font-size:1.3rem">$0</p>
                            <p style="font-weight:600;">The Basics, Free</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Unlimited Voice/Video Messages</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> 1 Voice Room</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Up to 5 Teammates</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> 30 Days of Retention</p>
                        </div>
                    </div>
                    <div class="col d-flex align-items-stretch">
                        <div class="card card-body shadow-sm" style="border-top: 5px solid #5e94ff">
                            <h3 class="mb-0 pb-0" style="font-weight:700">Standard</h3>
                            <p class="my-5" style="font-weight:700;font-size:1.3rem">$5/user/month</p>
                            <p style="font-weight:600;">Everything In Basic +</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Unlimited Voice Rooms</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Unlimited Teammates</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Unlimited Retention</p>
                        </div>
                    </div>
                    <div class="col d-flex align-items-stretch">
                        <div class="card card-body shadow-sm" style="border-top: 5px solid rgb(62, 207, 142)">
                            <h3 class="mb-0 pb-0" style="font-weight:700">Plus</h3>
                            <p class="my-5" style="font-weight:700;font-size:1.3rem">$10/user/month</p>
                            <p style="font-weight:600;">Everything In Standard +</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Unlimited Voice and Video Rooms</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Live Screensharing</p>
                            <p style="font-weight:500"><i class="fas fa-check pr-1" style="color:rgb(62, 207, 142)"></i> Message Transcription and Search<br><small>(coming soon)</small></p>
                        </div>
                    </div>
                </div>  

                <center><a class="btn btn-success shadow text-dark btn-lg my-5 font-weight-bold" href="/get_started" rel="nofollow">Start Now For Free</a></center>

                <h3 class="h1" style="font-weight:900;font-size:3.5rem;margin-top:10rem;margin-bottom:2.8rem">Ready to Work Where You Work</h3>
                <div class="row">
                    <div class="col-12 col-md-2 pl-0">
                        <center><img src="img/blab-app-icon.png" class="img img-fluid" style="height:150px" /></center>
                    </div>
                    <div class="col-12 col-md-10">
                        <p class="lead mt-5" style="font-weight:600;font-size:1.1rem">Available on every major browser, <br>and through our MacOS and Windows apps.</p>
                    </div>
                </div>
                
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
