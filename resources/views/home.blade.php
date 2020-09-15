@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">      
        @if ($is_billing_admin)
            <div class="card shadow-sm w-75 mb-5">
                <div class="card-body p-4 text-center">
                    <h1>Billing Information</h1>
                    @if ($billing->is_trial)
                        <p class="lead">{{ $organization->name }} is on a free trial of the {{ $billing->plan }} tier.<br>Your free trial ends on {{ $billing->trial_ends_at }}.</p>
                    @else
                        <p class="lead">{{ $organization->name }} is on the {{ $billing->plan }} tier.</p>
                    @endif
                    <a href="/billing" class="btn btn-primary text-light">
                        @if ($billing->plan == "Free")
                            Upgrade Account
                        @else  
                            Manage Billing
                        @endif
                    </a>
                </div>
            </div>  
        @endif

        <div class="card shadow-sm w-75">
            <div class="card-body p-4 text-center">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                <h1>Download Blab</h1>
                <div id="downloadInstructions">
                    <p class="my-4 lead">You're all set! Click the link below to download the latest version of Blab.<br><span class="text-muted">We'll automatically detect if you're using Windows or MacOS</span></p>

                    <a href="https://updater.blab.to" class="btn btn-primary btn-lg text-light btn-block" onclick="toggleMagicLinkDisplay()">Download Blab</a>

                    <hr class="my-5">
                    <p class="h4">Already have Blab installed and need to log in?</p>
                    <p class="lead">Click the button below to automatically sign into Blab.</p>
                    <a href="blab::/magic/login/{{ $magic_login_link }}" class="btn btn-success btn-lg text-light">Magic Login Link</a>
                </div>
                <div id="magicLink" class="d-none">
                    <p class="my-4 lead">Blab is downloading! Use the downloaded file to install Blab.<br><span class="text-muted">Once the installation is complete, you can click the magic link below to automatically log in to Blab.</p>
                    <a href="blab::/magic/login/{{ $magic_login_link }}" class="btn btn-primary btn-lg text-light btn-block">Magic Login Link</a>

                    <p class="text-muted mt-4">Something go wrong with the download? <a href="https://updater.blab.to">Try again.</a></p>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="application/javascript">

    function toggleMagicLinkDisplay() {
        var magicLinkDiv = document.getElementById("magicLink");
        magicLinkDiv.classList.remove("d-none");

        var downloadInstructions = document.getElementById("downloadInstructions");
        downloadInstructions.classList.add("d-none");

    }

</script>
@endsection
