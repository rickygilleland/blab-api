<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sendgrid_key = env('SENDGRID_API_KEY');
        $sg = new \SendGrid($sendgrid_key);

        $invite_email = new \SendGrid\Mail\Mail();
        $invite_email->setFrom("help@blab.to", "Blab");
        $invite_email->addTo($this->email->email, $this->email->name);

        if (isset($this->email->type) && $this->email->type == "text_only") {
            $invite_email->setSubject($this->email->subject);
            $invite_email->addContent(
                "text/html", $this->email->content
            );
        } else {
            $invite_email->addDynamicTemplateDatas($this->email->data);
            $invite_email->setTemplateId($this->email->template_id);
        }

        try {
            
            $response = $sg->send($invite_email);

        } catch (Exception $e) {
            //do something
        }
    }
}
