@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="card shadow-sm w-75 border-0 mb-4">
            <div class="card-body p-4">
                <h1 class="h2">Thank you for registering!</h1>
                <p class="sub-heading">Just a few quick steps to setup your account and you'll be on your way.</p>
            </div>
        </div>
        
        <div class="card shadow-sm w-75 border-0">
            <div class="card-body p-4">
                <h1 class="h3">Create your organization</h1>
                <p class="sub-heading">We'll use this to generate custom URLs for your organization, and to organize your teams for accounts supporting multiple teams (we'll get to teams in the next step). It will usually be the name of your company, but does not need to be formal.</p>
                <form method="post" action="/onboarding/organization">
                    @csrf
                    <label for="name" class="mt-2">Organization name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your organization name">
                    <button type="submit" class="btn btn-primary mt-3 btn-block py-2">Create Organization</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
