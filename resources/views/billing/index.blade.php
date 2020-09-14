@extends('layouts.onboarding')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">      
        <div class="card shadow-sm w-75 mb-5">
                <div class="card-body p-4 text-center">
                    <h1>Manage Billing</h1>
                    @if ($billing->is_trial)
                        <p class="lead">{{ $organization->name }} is on a free trial of the {{ $billing->plan }} tier.<br>Your free trial ends on {{ $billing->trial_ends_at }}.</p>
                        <p style="font-size:1.05rem">Your account will lose access to features like video chat, screen sharing, and rooms will be capped at 15 participants once your trial expires.</p>
                        <p style="font-size:1rem">You can ensure a seamless experience by upgrading your account now. Your account will remain on the {{ $billing->plan }} tier after the trial ends on {{ $billing->trial_ends_at }}, but you won't be charged until then.<br><br>We'll show you your billing details on the next page based on your account's current usage.</p>
                        <a href="/billing/upgrade" class="btn btn-primary text-light btn-lg btn-block">Start Upgrade Now</a>
                    @else
                        <p class="lead mt-4 mb-5">{{ $organization->name }} is on the {{ $billing->plan }} tier. Upgrade your account now to unlock additional features.</p>

                        @if ($billing->plan == "Free")
                            <div class="row">
                                <div class="col border-right">
                                    <h2>Free Tier<br><span style="font-size:1rem;font-weight:600">(Current Plan)</span></h2>
                                    <p style="font-weight:600">Unlimited Voice/Video Messages</p>
                                    <p style="font-weight:600">1 Voice Room</p>
                                    <p style="font-weight:600">Limit of 5 Teammates</p>
                                    <p style="font-weight:600">30 Days of Retention</p>
                                    <p class="mt-4" style="font-weight:600;font-size:1.2rem">Free</p>
                                </div>
                                <div class="col border-right">
                                    <h2>Standard Tier<br>&nbsp;</h2>
                                    <p style="font-weight:600">All Features of the Free Plan +</p>
                                    <p style="font-weight:600">Unlimited Voice Rooms</p>
                                    <p style="font-weight:600">Unlimited Teammates</p>
                                    <p style="font-weight:600">Unlimited Retention Retention</p>
                                    <p class="mt-4" style="font-weight:600;font-size:1.2rem">$5/user/month</p>
                                    <a href="/billing/upgrade/standard" class="btn btn-primary text-light btn-lg btn-block">Upgrade to Standard</a>
                                </div>
                                <div class="col border-right">
                                    <h2>Plus Tier<br>&nbsp;</h2>
                                    <p style="font-weight:600">All Features of the Standard Plan +</p>
                                    <p style="font-weight:600">Unlimited Voice and Video Rooms</p>
                                    <p style="font-weight:600">Live Screensharing</p>
                                    <p style="font-weight:600">Message Transcription and Search <small>(coming soon)</small></p>
                                    <p class="mt-4" style="font-weight:600;font-size:1.2rem">$10/user/month</p>
                                    <a href="/billing/upgrade/plus" class="btn btn-primary text-light btn-lg btn-block">Upgrade to Plus</a>
                                </div>
                            </div>  

                        @endif
                    @endif
                </div>
            </div>  
        </div>
    
    </div>
</div>

@endsection
