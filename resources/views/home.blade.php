@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="card shadow-sm w-75 border-0">
            <div class="card-body p-4 text-center">
                <h1>Download Water Cooler</h1>

                <div id="downloadInstructions">
                    <p class="my-4 lead">You're all set! Click the link below to download the latest version of Water Cooler.<br><span class="text-muted">We'll automatically detect if you're using Windows or MacOS</span></p>

                    <a href="https://updater.watercooler.work" class="btn btn-primary btn-lg text-light btn-block" onclick="toggleMagicLinkDisplay()">Download Water Cooler</a>
                </div>
                <div id="magicLink" class="d-none">
                    <p class="my-4 lead">Water Cooler is downloading! Use the downloaded file to install Water Cooler.<br><span class="text-muted">Once the installation is complete, you can click the magic link below to automatically log in to Water Cooler.</p>
                    <a href="watercooler::/magic/login/{{ $magic_login_link }}" class="btn btn-primary btn-lg text-light btn-block">Magic Login Link</a>

                    <p class="text-muted mt-4">Something go wrong with the download? <a href="https://updater.watercooler.work">Try again.</a></p>
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
