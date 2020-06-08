<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Event;

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
        $event = new Event();
        $event->type = $this->event->type;
        $event->user_id = $this->event->user_id;
        $event->organization_id = $this->event->organization_id;

        if (isset($this->event->room_id)) {
            $event->room_id = $this->event->room_id;
        }

        $event->save();
    }
}
