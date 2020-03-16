@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    
                    @if (env('APP_ENV') == 'production')
                        <center><a href="https://slack.com/oauth/v2/authorize?client_id=1000366406420.1003032710326&user_scope=identity.basic,identity.email,identity.avatar,identity.team&redirect_uri=https://watercooler.work/login/slack/callback"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x"></a></center>
                    @else
                        <center><a href="https://slack.com/oauth/v2/authorize?client_id=1000366406420.1003032710326&user_scope=identity.basic,identity.email,identity.avatar,identity.team&redirect_uri=https://w.test/login/slack/callback"><img alt="Add to Slack" height="40" width="139" src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x"></a></center>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
