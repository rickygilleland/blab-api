@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-5">

                    <h2 class="pb-2 text-center">Request an Invitation to Blab</h2>	
                    <p class="text-center lead mb-1" style="font-size:1.3rem;font-weight:600">To handle demand, we're accepting new users on a rolling basis.</p>
                    <p class="text-center lead pt-0 mt-0">Enter your name and email address and we'll send you an invitation to try Blab soon.</p>
                    <hr>

                    @if (isset($success))
                        <div class="alert alert-success">
                            <p class="text-center font-weight-bold">Thank you! We'll send you an email as soon as you reach the front of the line.</p>
                            <p class="text-center">We're sending out invitations on a rolling basis every day depending on demand, so keep an eye out for your invite!</p>
                        </div>

                        <a href="https://blab.to" class="btn btn-primary btn-block text-light">Return to Blab Home Page</a>
                    @else

                        <form method="POST" action="/invite">
                            @csrf
                            <label>First Name</label>
                            <input type="text" name="name" placeholder="First Name" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                            <label>Email Address</label>
                            <input type="text" name="email" placeholder="Email" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Request Invite</button>
                        
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Twitter universal website tag code -->
<script type="application/javascript">
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
// Insert Twitter Pixel ID and Standard Event data below
twq('init','o3sy2');
twq('track','PageView');
</script>
<!-- End Twitter universal website tag code -->
@endsection
