@extends('layouts.room')

@section('content')
<div class="container-fluid" id="video-body-container">

    <div id="loadingMsg" class="text-light">
        <h2 class="pt-5 mt-5 text-center h1">Loading {{ $room->name }}...</h2>
        <p style="font-size:4rem" class="text-center"><i class="fas fa-circle-notch fa-spin"></i></p>
    </div>

    <div id="waitingMsg" class="d-none">
        <h1 class="pt-5 mt-5 text-center text-light">You are the only one in {{ $room->name }}.</h1>
        <h2 class="text-center text-light">Waiting for other members to join...</h2>
    </div>

    <div class="row justify-content-center align-items-center d-none" id="video-media">
    </div>

    

    <div class="fixed-bottom" id="roomControls">
        <div class="row justify-content-center bg-dark py-2">
            <div class="bg-dark">
                <button class="btn btn-light mx-2" id="muteBtn"><i class="fas fa-microphone"></i></button>
                <button class="btn btn-light mx-2" id="hideVideoBtn"><i class="fas fa-video"></i></button>
                <button class="btn btn-danger mx-2" id="buttonLeave"><i class="fas fa-sign-out-alt"></i></button>
            </div>          
        </div>
    </div>
    <div id="local-media" class="fixed-bottom draggable float-right">
    </div>
</div>

@endsection
