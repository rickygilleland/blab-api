@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="card shadow-sm w-75 border-0">
            <div class="card-body p-4">
                <h1 class="h3">Create your team</h1>
                <p class="sub-heading">We'll display this in rooms and use it in your organization's URLs (i.e. <code>/{{ $organization->slug }}/team-name/room-name</code>). Feel free to name it anything you want, but it should generally reflect the name of the team that will be using Blab (i.e. Engineering or Support).</p><p class="sub-heading">Higher level plans have the ability to create multiple teams and restrict access to rooms at a team level.</p>
                <form method="post" action="/onboarding/team">
                    @csrf
                    <label for="name" class="mt-2">Team name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your team name">
                    <button type="submit" class="btn btn-primary mt-3 btn-block py-2">Create Team</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
