@extends('layouts.app', ['show_team_nav' => true, 'team' => $team])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="card w-100 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3">Available Rooms in {{ $team->name }}</h1>
                    </div>
                    <div class="col-md-4 d-flex flex-row-reverse">
                        <a class="btn btn-primary text-light" href="/o/{{ $user->organization->slug }}/{{ $team->slug }}/new">Create a New Room</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-100 shadow-sm border-0 mt-3">
            <div class="card-body p-4">

                @if (count($team->rooms) == 0)
                    <p class="sub-heading">You do not have any rooms.</p>
                @else

                    <table class="table table-striped">


                        @foreach ($team->rooms as $room)
                            <tr>
                                <td>{{ $room->name }}</td>
                                <td class="d-flex flex-row-reverse"><a class="btn btn-success text-light" href="/o/{{ $user->organization->slug }}/{{ $team->slug }}/{{ $room->slug }}">Enter Room</a>
                            </tr>   
                        @endforeach

                    </table>

                @endif
            </div>
        </div>

    </div>
</div>
@endsection
