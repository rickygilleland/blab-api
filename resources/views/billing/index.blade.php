@extends('layouts.onboarding')

@section('content')
<div class="container">
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
                        <p class="lead">{{ $organization->name }} is on the {{ $billing->plan }} tier.</p>
                    @endif
                </div>
            </div>  
        </div>
    
    </div>
</div>

@endsection
