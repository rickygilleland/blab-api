@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-6 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
					<h2 class="pb-2 text-center">Login to Water Cooler</h2>
					
					<hr>
					
					@if ($errors->any())
						@foreach ($errors->all() as $error)
							<div class="alert alert-danger">
			               		<p class="text-center mb-0">Oops! {{ $error }}</p>
							</div>
			            @endforeach
					@endif

					<form method="post" action="{{ route('login') }}">
						
						@csrf
  
						<input type="text" placeholder="Email" name="email" class="form-control dark-input mb-4 py-4 shadow-sm">
						
						<input type="password" placeholder="Password" name="password" class="form-control dark-input mb-4 py-4 shadow-sm">
						
						<button type="submit" class="btn btn-primary btn-block p-2 shadow">Login</button>
  						
					</form>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection