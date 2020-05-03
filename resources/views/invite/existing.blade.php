@extends('layouts.onboarding')

@section('content')
	<div class="row">
		
		<div class="col-md-6 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
					<h2 class="pb-2 text-center">Join {{$organization->name}} on Water Cooler</h2>
                    <p class="sub-heading text-center">Let's get your account created.</p>
					
					<hr>
					
					@if ($errors->any())
						@foreach ($errors->all() as $error)
							<div class="alert alert-danger">
			               		<p class="text-center mb-0">Oops! {{ $error }}</p>
							</div>
			            @endforeach
					@endif

					<form method="post" action="/register" enctype="multipart/form-data">	
						@csrf

                        <div class="row">
                            <div class="col-6">
                                <label>First Name</label>
						        <input type="text" placeholder="Eleanor" class="form-control dark-input mb-4 py-4 shadow-sm @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label>Last Name</label>
						        <input type="text" placeholder="Rigby" class="form-control dark-input mb-4 py-4 shadow-sm @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <label>Your Work Email Address</label>
						<input type="text" placeholder="eleanor@yourworkemail.com" class="form-control dark-input mb-4 py-4 shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label>Add a Picture of Yourself (Optional)</label>
						<input type="file" class="form-control-file @error('avatar') is-invalid @enderror" id="customFile" name="avatar">
					
                        @error('avatar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
						
                        <input type="hidden" name="invite_code" value="{{ $invite_code }}" />

						<button type="submit" id="continue-btn" class="btn btn-primary btn-block p-2 mt-4 shadow">Continue</button>
  						
					</form>
					
				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection