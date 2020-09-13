@extends('layouts.message')

@section('content')
	<div class="row">
		
		<div class="col-md-8 mx-auto">
			
			<div class="card shadow">
				
				<div class="card-body p-5">
			
                    <div class="row">

                        <div class="col-12 p-5">
                            <center>
                                <h2 class="mb-3">View {{ $message->user->first_name }}'s Blab</h2>
                                @if (strpos($message->attachment_mime_type, "audio") === false)
                                    <video controls src="{{ $message->attachment_url }}" />
                                @else
                                    <audio controls src="{{ $message->attachment_url }}" />
                                @endif
                            </center>
                        </div>

				</div>
				
			</div>
			
		</div>
		
	</div>

   
@endsection