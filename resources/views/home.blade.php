@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="card shadow-sm w-75 border-0">
            <div class="card-body p-4 text-center">
            <h1>Download Water Cooler</h1>

            <p class="my-4 sub-heading">You're all set! Click the link below to download the latest version of Water Cooler (it will automatically detect if you're using Windows or MacOS).</p>

            <a href="https://updater.watercooler.work" target="_blank" class="btn btn-primary btn-lg text-light btn-block">Download Water Cooler</a>

            <p class="my-4">Once you've installed Water Cooler, you can login normally, or click the magic link below to automatically login.</p>

            <a href="watercooler::/magic/login/{{ $magic_login_link }}">Magic Login Link</a>
                
            </div>
        </div>

    </div>
</div>
@endsection
