@extends('layouts.room')

@section('content')
<div class="container-fluid" id="video-body-container">

    <div class="row justify-content-center align-items-center" id="video-media">
    </div>

    <div class="fixed-bottom">
        <div class="row justify-content-center bg-dark py-2">
            <div class="bg-dark">
                <button class="btn btn-light mx-2" id="muteBtn"><i class="fas fa-microphone"></i></button>
                <button class="btn btn-light" id="hideVideoBtn"><i class="fas fa-video"></i></button>
            </div>          
        </div>
    </div>
</div>

@endsection
