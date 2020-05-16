<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Jobs\ProcessEmails;

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
        ->hourly()
        ->name('purge_expired_login_codes')
        ->onOneServer();

        $schedule->call(function () {
            $invites = DB::table('invites')
                ->where([
                    ['organization_id', null],
                    ['invite_sent', false]
                ])
                ->limit(5)
                ->get();

            $invited_count = 0;

            foreach ($invites as $invite) {

                $email = new \stdClass;
                $email->email = $invite->email;
                $email->name = $invite->name;
                $email->data = [
                    "subject" => $invite->name . ": You are Invited to Try Water Cooler",
                    "first_name" => $invite->name,
                    "invite_token" => base64_encode($invite->invite_code),
                ];
                $email->template_id = "d-4af02e391aff4fbba88409c2be1ccef5";

                ProcessEmails::dispatch($email);

                $update = DB::table('invites')->where('id', $invite->id)->update(['invite_sent' => true]);

                $invited_count++;
        
            }

            if ($invited_count > 0) {
                $email = new \stdClass;
                $email->type = "text_only";
                $email->email = "ricky@watercooler.work";
                $email->name = "Ricky Gilleland";
                $email->subject = "Invites Were Sent Out";
                $email->content = $invited_count . " invites were sent.";

                ProcessEmails::dispatch($email);
            }

        })
        ->hourly()
        ->name('send_invites')
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
