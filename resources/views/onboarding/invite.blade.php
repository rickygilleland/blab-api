@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="card shadow-sm w-75">
            <div class="card-body p-4">
                <h1 class="h2 pb-2 text-center">Who else works with you at {{ $organization->name }}?</h1>
                <hr>
                <p class="sub-heading">Enter the email address for any teammates you want to invite to use Water Cooler with you. You can enter multiple email addresses, separated by commas.</p>
      
                <form method="post" action="/onboarding/invite">
                    @csrf
                    <label for="name" class="mt-2">Enter your teammate's work email addresses</label>
                    <textarea type="text" class="form-control" name="emails" rows="5"></textarea>
                    <small class="form-text text-muted">To invite multiple people, separate each email with a comma. We'll send everyone an email with a unique invitation to join {{ $organization->name }} on Water Cooler.</small>
                    <button type="submit" class="btn btn-primary mt-3 btn-block py-2">Continue</button>
                </form>
                <hr>
                <center><a href="/home" class="btn btn-secondary text-light">Skip for Now</a></center>
            </div>
        </div>

    </div>
</div>
@endsection