<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Jobs\ProcessEmails;
use App\Jobs\ProcessUploadedVideo;
use App\Invite;
use App\User;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            //delete all of the expired or used login codes
            $codes = DB::table('login_codes')
                ->where('used', true)
                ->orWhere('created_at', '<', Carbon::now()->subMinutes(60))
                ->delete();
        })
        ->everyMinute()
        ->name('purge_expired_login_codes')
        ->onOneServer();

        $schedule->call(function () {
            $invites = DB::table('invites')
                ->where([
                    ['organization_id', null],
                    ['invite_sent', false],
                    ['name', '!=', 'Created By System'],
                    ['invite_type', null]
                ])
                ->orWhere([
                    ['organization_id', null],
                    ['invite_sent', true],
                    ['invite_accepted', false],
                    ['created_at', '>', Carbon::now()->subDays(7)],
                    ['updated_at', '<', Carbon::now()->subDays(3)],
                    ['name', '!=', 'Created By System'],
                    ['invite_type', null]
                ])
                ->limit(30)
                ->get();

            $invited_count = 0;
            $reminder_count = 0;

            foreach ($invites as $invite) {

                $subject = $invite->name . ": You are Invited to Try Blab";

                if ($invite->invite_sent == true) {
                    $subject = "Reminder: " . $invite->name . ": You are Invited to Try Blab";
                    $reminder_count++;

                    if ($invite->created_at <= Carbon::now()->subDays(6)) {
                        $subject = "Final " . $subject;
                    }
                }

                $email = new \stdClass;
                $email->email = $invite->email;
                $email->name = $invite->name;
                $email->data = [
                    "subject" => $subject,
                    "first_name" => $invite->name,
                    "invite_token" => base64_encode($invite->invite_code),
                ];
                $email->template_id = "d-4af02e391aff4fbba88409c2be1ccef5";

                ProcessEmails::dispatch($email);

                $updated_invite = Invite::where('id', $invite->id)->first();

                if ($updated_invite->invite_sent == false) {
                    $updated_invite->invite_sent = true;
                    $updated_invite->save();
                } 

                $updated_invite->touch();

                $invited_count++;
        
            }

            if ($invited_count > 0) {
                $email = new \stdClass;
                $email->type = "text_only";
                $email->email = "ricky@blab.to";
                $email->name = "Ricky Gilleland";
                $email->subject = "Invites Were Sent Out";
                $email->content = $invited_count . " invites were sent.\n " . $reminder_count++ . " were reminders.";

                ProcessEmails::dispatch($email);
            }

        })
        ->everyMinute()
        ->name('send_invites')
        ->onOneServer();

        $schedule->call(function () {
            $invites = DB::table('invites')
                ->where([
                    ['organization_id', '!=', null],
                    ['invite_accepted', false],
                    ['invite_sent', false],
                    ['created_at', '>', Carbon::now()->subDays(7)]
                ])
                ->orWhere([
                    ['organization_id', '!=', null],
                    ['invite_accepted', false],
                    ['created_at', '>', Carbon::now()->subDays(7)],
                    ['updated_at', '<', Carbon::now()->subDays(3)],
                ])
                ->limit(30)
                ->get();
    
            $invited_count = 0;
            $reminder_count = 0;
    
            foreach ($invites as $invite) {
    
                //make sure we don't send emails for the demo accounts
                $email = $invite->email;
                $domain = explode("@", $invite->email);
                if ($domain[1] == "acme.co") {
                    $email = "ricky@blab.to";
                } 

                $invite_user = User::where('id', $invite->invited_by)->first();

                if (!$invite_user) {
                    $updated_invite = Invite::where('id', $invite->id)->first();
                    $updated_invite->touch();
                    continue;
                }

                $subject = "Reminder: " . $invite_user->first_name . " has invited you to join " . $invite_user->organization->name . " on Blab";

                if ($invite->created_at <= Carbon::now()->subDays(6)) {
                    $subject = "Final " . $subject;
                }
    
                $invite_email = new \stdClass;
                $invite_email->name = "New Blab User";
                $invite_email->email = $email;
                $invite_email->data = [
                    "subject" => $subject,
                    "organization_name" => $invite_user->organization->name,
                    "inviter_name" => $invite_user->first_name,
                    "invite_token" => base64_encode($invite->invite_code),
                ];
                $invite_email->template_id = "d-ed053e9026d742eda4c66e5c5d6b2963";
    
                ProcessEmails::dispatch($invite_email);
    
                $updated_invite = Invite::where('id', $invite->id)->first();
                $updated_invite->touch();

                if ($updated_invite->invite_sent == false) {
                    $updated_invite->invite_sent = true;
                    $updated_invite->save();
                }
    
                $invited_count++;
        
            }
    
            if ($invited_count > 0) {
                $email = new \stdClass;
                $email->type = "text_only";
                $email->email = "ricky@blab.to";
                $email->name = "Ricky Gilleland";
                $email->subject = "Organization Invite Reminders Were Sent Out";
                $email->content = $invited_count . " reminders were sent.";
    
                ProcessEmails::dispatch($email);
            }
    
        })
        ->everyMinute()
        ->name('send_organization_invite_reminders')
        ->onOneServer();

        $schedule->call(function () {
            $attachments = DB::table('attachments')
                ->where([
                    ['processed', false],
                    ['mime_type', 'video/webm'],
                    ['created_at', '<', Carbon::now()->subMinutes(15)]
                ])
                ->get();

            foreach ($attachments as $attachment) {
                dispatch(ProcessUploadedVideo($attachment));
            }
            
        })
        ->everyMinute()
        ->name('process_failed_videos')
        ->onOneServer();

    }


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
