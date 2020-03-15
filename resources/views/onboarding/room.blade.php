@extends('layouts.onboarding')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="card shadow-sm w-75 border-0">
            <div class="card-body p-4">
                <h1 class="h3">Create your first room</h1>
                <p class="sub-heading">You're almost done! This is the last step. Create your first room to get started with your new Water Cooler account.</p>
                <p class="sub-heading">Rooms are places where you and your team can meet via video and can be a place where you hang out, or it can represent re-ocurring meetings such as a daily stand up.</p>
                <p class="sub-heading">You can create up to 5 rooms, or upgrade your plan later on to create more. By default, everyone on your team will be able to access any room.</p>
                <form method="post" action="/onboarding/room">
                    @csrf
                    <label for="name" class="mt-2">Room name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your room name">
                    <button type="submit" class="btn btn-primary mt-3 btn-block py-2">Create Room</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
