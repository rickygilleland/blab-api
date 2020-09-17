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
                        <p class="lead mt-4 mb-5">{{ $organization->name }} is on the {{ $billing->plan }} tier. Upgrade your account now with a 7 day free trial to unlock additional features.</p>

                        @if ($billing->plan == "Free")

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
                                        <a href="/billing/upgrade/standard" class="btn btn-primary text-light btn-lg btn-block mt-auto">Upgrade to Standard</a>
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
                                        <a href="/billing/upgrade/plus" class="btn btn-primary text-light btn-lg btn-block mt-auto">Upgrade to Plus</a>
                                    </div>
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
