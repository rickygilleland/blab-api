require('./bootstrap');

import 'jquery-ui/ui/widgets/draggable.js';
import 'jquery-ui/ui/widgets/resizable.js';

/* twilio */

function adjustVideoSize() {
    var height = $(window).height();
    var width = $(window).width();

    height = height - 180;
    width = width - 110;

    var totalVideoContainers = $('.video').length;

    if (totalVideoContainers == 1) {
        $('.video').height(height);
        $('.video').width("auto");
    }

    if (totalVideoContainers == 2) {
        width = width / 2;
        $('.video').height("auto");
        $('.video').width(width);
    }

    if (totalVideoContainers > 2 && totalVideoContainers <= 6) {
        height = height / 2;
        $('.video').height(height);
    }

    if (totalVideoContainers > 6 && totalVideoContainers <= 12) {
        height = height / 3;
        $('.video').height(height);
    }

    if (totalVideoContainers > 2 && totalVideoContainers <= 4) {
        $('.video').width("auto");
    }

    if (totalVideoContainers > 4 && totalVideoContainers <= 6) {
        width = width / 3;
        $('.video').width(width);
    }

    if (totalVideoContainers > 6 && totalVideoContainers <= 9) {
        width = width / 3;
        $('.video').width(width);
    }

    if (totalVideoContainers > 9 && totalVideoContainers <= 12) {
        width = width / 4;
        $('.video').width(width);
    }
}

// Attach the Track to the DOM.
function attachTrack(track, container) {
    const trackElement = track.attach();
    trackElement.classList.add('video');
    container.appendChild(trackElement);
}

// Attach array of Tracks to the DOM.
function attachTracks(tracks, container) {
    tracks.forEach(function(track) {
        attachTrack(track, container);
    });
}

// Detach given track from the DOM
function detachTrack(track) {
    track.detach().forEach(function(element) {
        element.remove();
    });
}

// A new RemoteTrack was published to the Room.
function trackPublished(publication, container) {
    if (publication.isSubscribed) {
        attachTrack(publication.track, container);
    }
    publication.on('subscribed', function(track) {
        attachTrack(track, container);
    });
    publication.on('unsubscribed', detachTrack);
}

// A RemoteTrack was unpublished from the Room.
function trackUnpublished(publication) {
}

// A new RemoteParticipant joined the Room
function participantConnected(participant, container) {
    participant.tracks.forEach(function(publication) {
        trackPublished(publication, container);
    });
    participant.on('trackPublished', function(publication) {
        trackPublished(publication, container);
    });
    participant.on('trackUnpublished', trackUnpublished);
}

// Detach the Participant's Tracks from the DOM.
function detachParticipantTracks(participant) {
    var tracks = getTracks(participant);
    tracks.forEach(detachTrack);
}

// When we are about to transition away from this page, disconnect
// from the room, if joined.
window.addEventListener('beforeunload', leaveRoomIfJoined);

 // Get the Participant's Tracks.
 function getTracks(participant) {
    return Array.from(participant.tracks.values()).filter(function(publication) {
        return publication.track;
    }).map(function(publication) {
        return publication.track;
    });
}

// Successfully connected!
function roomJoined(room) {

    window.room = activeRoom = room;

    // Attach LocalParticipant's Tracks, if not already attached.
    var remoteMediaContainer = document.getElementById('video-media');
    if (!remoteMediaContainer.querySelector('video')) {

        var participantdiv = document.createElement('div');
        participantdiv.id = room.localParticipant.sid;
        participantdiv.classList.add("col");
        participantdiv.classList.add("rounded");

        remoteMediaContainer.appendChild(participantdiv);

        attachTracks(getTracks(room.localParticipant), participantdiv);

        adjustVideoSize();

    }

    // Attach the Tracks of the Room's Participants.
    room.participants.forEach(function(participant) {

        var participantdiv = document.createElement('div');
        participantdiv.id = participant.sid;
        participantdiv.classList.add("col");
        participantdiv.classList.add("rounded");

        remoteMediaContainer.appendChild(participantdiv);

        participantConnected(participant, participantdiv);

        adjustVideoSize();

        setTimeout(() => { adjustVideoSize(); }, 500);

    });

    room.on('participantConnected', function(participant) {

        var participantdiv = document.createElement('div');
        participantdiv.id = participant.sid;
        participantdiv.classList.add("col");
        participantdiv.classList.add("rounded");

        remoteMediaContainer.appendChild(participantdiv);

        participantConnected(participant, participantdiv);

        adjustVideoSize();

        setTimeout(() => { adjustVideoSize(); }, 500);
    });

    // When a Participant leaves the Room, detach its Tracks.
    room.on('participantDisconnected', function(participant) {
        detachParticipantTracks(participant);
        $('#'+participant.sid).remove();
        adjustVideoSize();
    });

    // Once the LocalParticipant leaves the room, detach the Tracks
    // of all Participants, including that of the LocalParticipant.
    room.on('disconnected', function() {
        if (previewTracks) {
        previewTracks.forEach(function(track) {
            track.stop();
        });
        previewTracks = null;
        }
        detachParticipantTracks(room.localParticipant);
        room.participants.forEach(detachParticipantTracks);
        activeRoom = null;
    });
}

document.getElementById('muteBtn').onclick = function() {
    const muteBtn = $('#muteBtn');
    const mute = muteBtn.hasClass("btn-light");
    const localUser = room.localParticipant;
    getTracks(localUser).forEach(function(track) {
      if (track.kind === 'audio') {
        if (mute) {
          track.disable();
        } else {
          track.enable();
        }
      }
    });
    if (mute) {
        muteBtn.removeClass('btn-light');
        muteBtn.addClass('btn-danger');
        muteBtn.html('<i class="fas fa-microphone-slash"></i>');
    } else {
        muteBtn.removeClass('btn-danger');
        muteBtn.addClass('btn-light');
        muteBtn.html('<i class="fas fa-microphone"></i>');
    }
  }

  document.getElementById('hideVideoBtn').onclick = function() {
    const hideVideoBtn = $('#hideVideoBtn');
    const hide = hideVideoBtn.hasClass("btn-light");
    const localUser = room.localParticipant;
    getTracks(localUser).forEach(function(track) {
      if (track.kind === 'video') {
        if (hide) {
          track.disable();
        } else {
          track.enable();
        }
      }
    });
    if (hide) {
        hideVideoBtn.removeClass('btn-light');
        hideVideoBtn.addClass('btn-danger');
        hideVideoBtn.html('<i class="fas fa-video-slash"></i>');
    } else {
        hideVideoBtn.removeClass('btn-danger');
        hideVideoBtn.addClass('btn-light');
        hideVideoBtn.html('<i class="fas fa-video"></i>');
    }
  }

 // Leave Room.
 function leaveRoomIfJoined() {
    if (activeRoom) {
        activeRoom.disconnect();
    }
}

$( function() {
    if (typeof is_room != 'undefined' && is_room == true) {
        
        var connectOptions = {
            name: roomName,
        };
    
        if (previewTracks) {
            connectOptions.tracks = previewTracks;
        }
    
        // Join the Room with the token from the server and the
        // LocalParticipant's Tracks.
        Video.connect(identity, connectOptions).then(roomJoined, function(error) {
        });

        window.onresize = function(event) {
            adjustVideoSize();
        }
    }
});

/* end twilio */

$(function() {
    $( ".drag" ).draggable();
    $( ".resizable" ).resizable();
});