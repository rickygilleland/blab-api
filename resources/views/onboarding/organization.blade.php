@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="card shadow-sm w-75">
            <div class="card-body p-4">
                <h1 class="h2 pb-2 text-center">Thank you for registering!</h1>
                <hr>
                <p class="sub-heading text-center">We've created your account. Now, what should we call your organization?</p>
      
                <form method="post" action="/onboarding/organization">
                    @csrf
                    <label for="name" class="mt-2">Organization Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your organization name">
                    <small class="form-text text-muted">We'll show this in menus and headings, as well as in invites to your teammates. We recommend using the name of your company.</small>
                    <button type="submit" class="btn btn-primary mt-3 btn-block py-2">Continue</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
