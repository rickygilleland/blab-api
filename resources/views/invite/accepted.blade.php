@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-6 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
					<h2 class="pb-2 text-center">Invited Already Used</h2>
                    <p class="sub-heading text-center">This invite has already been used. Try logging in instead.</p>
					
					<hr>
					
					<a href="/login" class="btn btn-primary text-light btn-block">Log In</a>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection