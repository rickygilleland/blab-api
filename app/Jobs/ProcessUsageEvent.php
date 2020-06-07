<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Usage;

class ProcessUsageEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $usage = new Usage();
        $usage->type = $this->event->type;
        $usage->user_id = $this->event->user_id;
        $usage->organization_id = $this->event->organization_id;

        if (isset($this->event->room_id)) {
            $usage->room_id = $this->event->room_id;
        }

        $usage->save();
    }
}
