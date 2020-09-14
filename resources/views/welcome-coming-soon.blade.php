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
        </style>

    </head>
    <body class="px-md-5 px-2">

        <div class="container-fluid">
            <div class="max-width:1400px">
                <nav class="navbar navbar-expand-lg navbar-light text-dark">
                    <a class="navbar-brand" href="#" style="font-weight:900;font-size:1.75rem;"><img src="img/blab-app-icon.png" class="img img-fluid" style="height:60px"></a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="#features"><i class="fas fa-flag"></i> Features</a>
                            </li>   
      <!--                       <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="#pricing"><i class="fas fa-tags"></i> Pricing</a>
                            </li>    -->
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem;color:#343a40!important" href="/login">Log In</a>
                            </li>   
                            <li class="nav-item">
                                <a class="btn btn-success shadow text-dark my-3 font-weight-bold" href="/get_started" rel="nofollow">Get Started for Free</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>      

        <div class="container-fluid" style="background-color:#0076ff;color:white;border-radius:50px">
            <div style="max-width:1400px" class="mx-auto py-3 py-4">

                <div class="row my-md-5">
                    <div class="col-md-5 order-sm-12">
                        <h1 class="text-center align-middle mt-md-5" style="font-weight:900;">Voice First Communication for Teams</h1>
                        <h2 class="text-center mt-4" style="font-weight:500;font-size:1.3rem;line-height:1.4">Stay connected with your team no matter what your schedule with our voice first messages.<br><br>And when you do need some face time, pop into a voice or video room to hang out with your teammates.</h2>
                        <center><a class="btn btn-success shadow btn-lg text-dark my-2 font-weight-bold" href="/get_started" rel="nofollow">Get Started for Free</a></center>
                    </div>
                    <div class="col-md-7 order-sm-1">
                        <img src="/img/main-hero-demo.gif" class="img img-fluid rounded">
                    </div>
                </div>
                
            </div>
        </div>

        <!--
        <div class="container-fluid" style="background-color:#fff;color:black" id="features">
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

        <div class="container-fluid" style="background-color:#0076ff;color:white;border-radius:50px">
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

        <div class="container-fluid" style="background-color:#fff;color:black" id="pricing">
            <div style="max-width:1400px" class="mx-auto py-5">
               <h3 class="h1 text-center mt-5 mb-3" style="font-weight:800"><i class="fas fa-tags"></i> Blab Pricing</h3>
                <p class="lead text-center mb-1" style="font-weight:500;font-size:1.45rem">Flexible pricing with no commitments.</p>
                <p class="text-center pt-0 mb-5" style="font-size:1.1rem">Start off with a free account and upgrade when you outgrow it. All paid plans come with a 30 day money back guarantee.</p>
                
                <div class="row">
                    <div class="col border-right text-center">
                        <h2 style="font-weight:700">Free Plan<br><span style="font-size:1rem;font-weight:600">&nbsp;</span></h2>
                        <p style="font-weight:600">Unlimited Voice/Video Messages</p>
                        <p style="font-weight:600">1 Voice Room</p>
                        <p style="font-weight:600">Limit of 5 Teammates</p>
                        <p style="font-weight:600">30 Days of Retention</p>
                        <p class="mt-4 text-center" style="font-weight:600;font-size:1.2rem">Free</p>
                    </div>
                    <div class="col border-right text-center">
                        <h2 style="font-weight:700">Standard Plan<br>&nbsp;</h2>
                        <p style="font-weight:600">All Features of the Free Plan +</p>
                        <p style="font-weight:600">Unlimited Voice Rooms</p>
                        <p style="font-weight:600">Unlimited Teammates</p>
                        <p style="font-weight:600">Unlimited Retention Retention</p>
                        <p class="mt-4 text-center" style="font-weight:600;font-size:1.2rem">$5/user/month</p>
                    </div>
                    <div class="col  text-center">
                        <h2 style="font-weight:700">Plus Plan<br>&nbsp;</h2>
                        <p style="font-weight:600">All Features of the Standard Plan +</p>
                        <p style="font-weight:600">Unlimited Voice and Video Rooms</p>
                        <p style="font-weight:600">Live Screensharing</p>
                        <p style="font-weight:600">Message Transcription and Search <small>(coming soon)</small></p>
                        <p class="mt-4 text-center" style="font-weight:600;font-size:1.2rem">$10/user/month</p>
                    </div>
                </div>  

                <center><a class="btn btn-success shadow text-dark btn-lg mt-5 font-weight-bold" href="/get_started" rel="nofollow">Get Started for Free</a></center>

                <div class="d-flex justify-content-center" style="margin-top:3rem;">
                    <div>
                        <center><img src="img/blab-app-icon.png" class="img img-fluid" style="height:150px" /></center>
                    </div>
                    <div class="w-50 px-4">
                        <h3 class="h1" style="font-weight:800;margin-top:1rem">Cross Platform and Ready to Work Where You Do</h3>
                        <p class="lead" style="font-weight:500">Available on every major browser, and through our MacOS and Windows apps.</p>
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
