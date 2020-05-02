@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-6 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
					<h2 class="pb-2 text-center">Join Organization</h2>
					
					<hr>

                    @if (isset($error))
                        <div class="alert alert-danger">
                            <p class="text-center mb-0">Oops! {{ $error }}</p>
                        </div>
                    @endif

					<form method="post" action="/login">
						
						@csrf
  
						<input type="text" placeholder="Email" name="email" class="form-control dark-input mb-4 py-4 shadow-sm">
						
						<button type="submit" class="btn btn-primary btn-block p-2 shadow">Continue</button>
  						
					</form>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection