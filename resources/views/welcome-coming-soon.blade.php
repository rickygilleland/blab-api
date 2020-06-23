<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Water Cooler</title>

        <script src="/js/app.js" defer></script>

        <link href="/css/app.css" rel="stylesheet">

        <script src="https://kit.fontawesome.com/584495cc88.js"></script>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <meta property="og:title" content="Water Cooler" />
        <meta property="og:type" content="website" />
        <meta property="og:description" content="We deliver always available rooms for office banter, meetings, or quick questions." />
        <meta property="og:url" content="https://watercooler.work" />
        <meta property="og:image" content="https://watercooler.work/img/og-hero.png" />

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
          posthog.init('64tUVTgJhFVIV7BADDLYHN-zG2Ja1yqzOI_SE8Pytc4', {api_host: 'https://analytics.watercooler.work'})
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
    <body>

        <div class="container-fluid" style="background-color:#3777ff;color:white">
            <div style="max-width:1400px" class="mx-auto pb-3">

                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="#" style="font-weight:900;font-size:1.75rem;color:#fff!important"><img src="img/water_cooler.png" class="img img-fluid" style="height:60px">Water Cooler</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem" href="#features"><i class="fas fa-flag"></i> Features</a>
                            </li>   
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem" href="#pricing"><i class="fas fa-tags"></i> Pricing</a>
                            </li>   
                            <li class="nav-item">
                                <a class="nav-link text-light font-weight-bold pr-5" style="padding-top:1.35rem;font-size:1.1rem" href="/login">Log In</a>
                            </li>   
                            <li class="nav-item">
                                <a class="btn btn-success shadow text-dark my-3 font-weight-bold" href="/invite" rel="nofollow">Get Started for Free</a>
                            </li>
                        </ul>
                    </div>
                </nav>


                <div class="row my-md-5">
                    <div class="col-md-5 order-sm-12">
                        <h1 class="text-center align-middle mt-md-5" style="font-weight:900;">Always Available Communication for your Team</h1>
                        <h2 class="text-center mt-4" style="font-weight:600;font-size:1.55rem">Voice-only by default and perfect for office banter, meetings, or quick questions.</h2>
                        <center><a class="btn btn-success shadow btn-lg text-dark my-3 font-weight-bold" href="/invite" rel="nofollow">Get Started for Free</a></center>
                    </div>
                    <div class="col-md-7 order-sm-1">
                        <img src="/img/main-hero-screenshot.png" class="img img-fluid rounded">
                    </div>
                </div>
                
            </div>
        </div>

        <div class="container-fluid" style="background-color:#fff;color:black" id="features">
            <div style="max-width:1400px" class="mx-auto py-5">
            
                <div class="row mb-5 clearfix">
                    <div class="col-md-6">
                        <h3 class="h2" style="font-weight:800;margin-top:5rem">Always Available, No Codes Required</h3>
                        <p class="lead" style="font-weight:500">Create rooms that are available to the entire team, or restricted to a few select team members. Great for general office chit-chat, recurring meetings, or 1:1 conversations with your co-workers.</p>
                        <p class="lead font-weight-bold">Rooms are always available and never require a special code to join or for a specific host to be present.</p>         
                        <p class="lead" style="font-weight:500">Whenever a member of your team is in a different timezone than yours, we automatically overlay their local time whenever they're in a room.</p>  
                    </div>
                    <div class="col-md-6">
                        <img src="/img/rooms-screenshot.png" style="max-height:400px" class="img img-fluid rounded shadow float-right" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="background-color:#3777ff;color:white">
            <div style="max-width:1400px" class="mx-auto py-5">
                <div class="row mb-5 clearfix">
                    <div class="col-md-6">
                        <h3 class="h2" style="font-weight:800;margin-top:3.25rem">Secure HD Video and Audio</h3>
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

        <div class="container-fluid" style="background-color:#fff;color:black" id="community">
            <div style="max-width:1400px" class="mx-auto py-5">
                <div class="row mb-5 clearfix">
                    <div class="col-md-6">
                        <h3 class="h2" style="font-weight:800;margin-top:5.25rem">Simple and Fast Screen Sharing</h3>
                        <p class="lead" style="font-weight:500">Share your entire screen, or just a single window. With compact and simple controls, we stay out of your way and so you can work.</p>
                        <p class="lead" style="font-weight:500">Your screen is shared in full HD through the same secure infrastructure powering our HD video and voice chat.</p> 
                        <p class="small">*Video quality and resolution may be reduced at times depending on network conditions.</p>             
                    </div>
                    <div class="col-md-6">
                        <img src="/img/screen-sharing-demo.gif" style="max-height:400px" class="img img-fluid rounded shadow float-right" />
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="background-color:#3777ff;color:white">
            <div style="max-width:1400px" class="mx-auto py-5">
                <h3 class="h1 text-center mt-5" style="font-weight:800">Water Cooler is Cross Platform</h3>
                <p class="lead text-center" style="font-weight:500">Available on Windows and MacOS. Linux support coming soon.</p>
                <p class="text-center" style="font-size:6.5rem"><i class="fab fa-apple"></i> <i class="fab fa-windows mx-5"></i></p>
            </div>
        </div>

        <div class="container-fluid" style="background-color:#fff;color:black" id="pricing">
            <div style="max-width:1400px" class="mx-auto py-5">
                <h3 class="h1 text-center mt-5 mb-3" style="font-weight:800"><i class="fas fa-tags"></i> Water Cooler Pricing</h3>
                <p class="lead text-center mb-1" style="font-weight:500;font-size:1.45rem">Flexible pricing with no commitments.</p>
                <p class="text-center pt-0 mb-5" style="font-size:1.1rem">Start off with a free account and upgrade when you outgrow it. 7 day free trial available for our Standard Plan.</p>
                <div class="table-responsive">
                    <table class="table w-75 mx-auto shadow-sm" style="border-top: 5px solid #3777ff">
                        <tr>
                            <th class="border-top-0" style="width:33.33%">&nbsp;</th>
                            <th class="border-left border-top-0 border-bottom-0 pt-4 pb-0" style="width:33.33%">
                                <h3 style="font-weight:800">Free</h3>
                            </th>
                            <th class="border-left border-top-0 pt-4 pb-0" style="width:33.33%">
                                <h3 style="font-weight:800">Standard</h3>
                                <p class="lead mb-1"><s><span style="font-weight:700">$8/active user</span><small> per month</small></s></p>
                                <p>Free until June 24th, 2020</p>
                            </th>
                        </tr>
                        <tr>
                            <td class="border-top-0">
                                <h4 class="pt-0">Usage</h4>
                            </td>
                            <td class="border-top-0 border-left">&nbsp;</td>
                            <td class="border-top-0 border-left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <p class="lead">Users</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Unlimited
                            </td>
                            <td class="pricing-table-feature border-left">
                                Unlimited
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td>
                                <p class="lead">Rooms</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Unlimited
                            </td>
                            <td class="pricing-table-feature border-left">
                                Unlimited
                            </td>
                        </tr>
                        <tr>
                            <td class="border-top-0">
                                <h4 class="pt-4">Features</h4>
                            </td>
                            <td class="border-top-0 border-left">&nbsp;</td>
                            <td class="border-top-0 border-left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <p class="lead">Private Rooms</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-check pr-1" style="color:green"></i>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-check pr-1" style="color:green"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="lead">Video Chat</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Voice Only
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-check pr-1" style="color:green"></i>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="lead">Max Room Participants</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                15
                            </td>
                            <td class="pricing-table-feature border-left">
                                100 <br><small>(Video participants limited to 20, all others voice only)</small>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td>
                                <p class="lead">1:1 and Group Calls</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Coming Soon
                            </td>
                            <td class="pricing-table-feature border-left">
                                Coming Soon
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td>
                                <p class="lead">Screen Sharing</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-ban pr-1" style="color:red"></i>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-check pr-1" style="color:green"></i>
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td>
                                <p class="lead">Live Broadcast to Team</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-ban pr-1" style="color:red"></i>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Coming Soon
                            </td>
                        </tr>
                        <tr class="border-bottom">
                            <td>
                                <p class="lead">Send Video Messages to Teammates</p>
                            </td>
                            <td class="pricing-table-feature border-left">
                                <i class="fas fa-ban pr-1" style="color:red"></i>
                            </td>
                            <td class="pricing-table-feature border-left">
                                Coming Soon
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
                
        <div class="container-fluid" style="background-color:#3777ff;color:white">
            <div style="max-width:1400px" class="mx-auto py-5">
                <p class="text-center" style="font-weight:600;font-size:1.4rem;">Interested in being one of the first to try Water Cooler for free?<br> Click the button below to get started.</p>
                <center><a class="btn btn-success shadow text-dark btn-lg mt-3 font-weight-bold" href="/invite" rel="nofollow">Get Started for Free</a></center>

                <p class="text-center mt-5 font-weight-bold" style="font-size:1.1rem">Questions? Drop us a line at hello (at) watercooler.work</p>
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
