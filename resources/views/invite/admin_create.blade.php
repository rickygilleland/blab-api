@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-5">

                    <h2 class="pb-2 text-center">Create a New Account Invitation</h2>	
                    <hr>

                    @if (isset($invite_link))
                        <div class="alert alert-success">
                            <p class="text-center mb-0 mt-3">The invite was created successfully:</p>
                            <p>{{ $email }}<br>{{ $invite_link }}</p>
                        </div>
                    @endif

                    <form method="POST" action="/admin/invite/create">
                        @csrf

                        <input type="text" name="name" placeholder="First Name" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                        <input type="text" name="email" placeholder="Email" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                        <button type="submit" class="btn btn-primary btn-block">Create Invite</button>
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
