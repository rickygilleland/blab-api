@extends('layouts.onboarding')

@section('content')
	<div class="row">

		<div class="col-md-6 mx-auto">

			<div class="card shadow">

				<div class="card-body p-5">

					<h2 class="pb-2 text-center">Login to Blab</h2>

					<hr>

					@if (isset($error))
                        <div class="alert alert-danger">
			               	<p class="text-center mb-0">Oops! {{ $error }}</p>
						</div>

                    @endif

				</div>

			</div>

		</div>

	</div>


@endsection
