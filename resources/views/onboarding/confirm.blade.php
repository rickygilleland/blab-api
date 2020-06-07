@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-6 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
					<h2 class="pb-2 text-center">Confirm Your Account</h2>
					
					<hr>
					
					@if (isset($error))
                        <div class="alert alert-danger">
			               	<p class="text-center mb-0">Oops! {{ $error }}</p>
						</div>
                    @else
                        <div class="alert alert-success text-center" role="alert">
                            We sent a confirmation code {{ $email }}. Enter the temporary confirmation code below to confirm your account.
                        </div>
                    @endif

					<form method="post" action="/onboarding/confirm">
						
						@csrf
  
						<input type="text" placeholder="Code" name="token" class="form-control dark-input mb-4 py-4 shadow-sm">

						<button type="submit" class="btn btn-primary btn-block p-2 shadow">Confirm Account</button>
                        <center><a href="/onboarding/confirm" class="btn btn-secondary mt-5 shadow text-light">Resend Code</a></center>
  						
					</form>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection