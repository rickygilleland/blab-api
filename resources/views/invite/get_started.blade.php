@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-8 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
                    <div class="row">

                        <div class="col-md-6 p-5">
                            <h2>My team isn't on Water Cooler Yet.</h2>
                            <p class="lead">Create a new account for your team.</p>
                            <a href="/invite" class="btn btn-block btn-primary text-light btn-lg">Create a new Water Cooler Organization</a>
                        </div>

                        <div class="col-md-6 border-left p-5">
                            <h2>My team is already on Water Cooler.</h2>
                            <p class="lead">Water Cooler organizations are invite only. Please ask someone on your team to invite you.</p>
                        </div>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection