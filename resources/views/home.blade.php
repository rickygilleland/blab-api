@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h1>Download Water Cooler</h1>

                <p>Once your download is complete, open Water Cooler and click the link below to login.</p>

                <a href="watercooler::/magic/login/{{ $magic_login_link }}">Login</a>
                
            </div>
        </div>
    </div>
</div>
@endsection
