@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-5">

                    <h2 class="pb-2 text-center">Request an Invitation to Water Cooler</h2>	
                    <hr>

                    @if (isset($success))
                        <div class="alert alert-success">
                            <p class="text-center font-weight bold">Thank you! We'll send you an email as soon as you reach the front of the line. We're sending out invitations on a rolling basis every day depending on demand.</p>
                        </div>
                    @else

                        <form method="POST" action="/invite">
                            @csrf

                            <input type="text" name="name" placeholder="First Name" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                            <input type="text" name="email" placeholder="Email" class="form-control dark-input mb-4 py-4 shadow-sm" required/>
                            <button type="submit" class="btn btn-primary btn-block">Request Invite</button>
                        
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
