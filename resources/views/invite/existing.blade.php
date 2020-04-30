@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                       
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
