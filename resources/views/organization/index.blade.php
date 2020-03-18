@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="card w-100 shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-8">
                        <h1 class="h3"><strong>{{ $user->organization->name }}</strong> Teams</h1>
                    </div>
                    <div class="col-md-4 d-flex flex-row-reverse">
                        <button class="btn btn-primary text-light" data-toggle="modal" data-target="#newTeamModal"><i class="fas fa-plus"></i> Add Team</button>
                    </div>
                </div>
            </div>
        </div>

        @foreach($user->organization->teams as $team)
        
            <div class="card w-100 shadow-sm border-0 mt-3">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="h3"><strong>{{ $team->name }}</strong> Rooms</h1>
                        </div>
                        <div class="col-md-4 d-flex flex-row-reverse">
                            <button class="btn btn-primary text-light" data-toggle="modal" data-team="{{ $team->id }}" data-target="#newRoomModal"><i class="fas fa-plus"></i> Add Room</button>
                        </div>
                    </div>

                    <hr class="py-2">

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
        
        @endforeach

    </div>
</div>

@if (env('APP_ENV') == 'local')
    <!-- New Team Modal -->
    <div class="modal fade" id="newTeamModal" tabindex="-1" role="dialog" aria-labelledby="newTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTeamModalLabel">Create a New Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="/team">
                <div class="modal-body">
                    @csrf
                    <label for="teamName">Team Name</label>
                    <input type="text" name="name" placeholder="Team Name" class="form-control" id="teamName">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@else   
     <!-- New Team Modal -->
     <div class="modal fade" id="newTeamModal" tabindex="-1" role="dialog" aria-labelledby="newTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newTeamModalLabel">Create a New Team</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="/team">
                <div class="modal-body">
                    <p class="sub-heading">Oops! An Enterprise Plan (coming soon) is required to create multiple teams.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
            </div>
        </div>
    </div>

@endif

@if (env('APP_ENV') != 'local')
    <!-- New Room Modal -->
    <div class="modal fade" id="newRoomModal" tabindex="-1" role="dialog" aria-labelledby="newRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoomModalLabel">Create a New Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="/room">
                <div class="modal-body">
                    <p class="sub-heading">The ability to add multiple rooms is coming soon!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@else
    <!-- New Room Modal -->
    <div class="modal fade" id="newRoomModal" tabindex="-1" role="dialog" aria-labelledby="newRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRoomModalLabel">Create a New Room</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="/room">
                <div class="modal-body">
                    @csrf
                    <label for="roomName">Room Name</label>
                    <input type="text" name="name" placeholder="Room Name" class="form-control" id="roomName">
                    <input type="hidden" name="team_name" value="" id="newRoomTeamId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endif
@endsection

@section('js-script')
<script>

    (function() {

        $('#newRoomModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var team_id = button.data('team')
            
            console.log(event);

            var modal = $(this)
            $('#newRoomTeamId').val(team_id)
        });
    });

</script>
@endsection
