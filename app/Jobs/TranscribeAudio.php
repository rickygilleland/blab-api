<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Events\DirectMessageUpdated;
use TitasGailius\Terminal\Terminal;
use Log;

class TranscribeAudio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $audio = Storage::disk('spaces')->get($this->message->attachment_path);

        //write the raw audio content to /tmp
        $dir = sys_get_temp_dir();
        $audio_tmp_file = tempnam($dir, "audio_transcribe_");
        file_put_contents($audio_tmp_file, $audio);

        Log::info("TRANSCRIBE STARTED");

        Log::info("TRANSCRIBE AUDIO PATH" . $audio_tmp_file);
        Log::info("TRANSCRIBE WORKING PATH" . base_path());

        if (file_exists(base_path() . "/deepspeech-0.8.2-models.pbmm") === false) {
            Log::info("Downloading pbmm");
            $download = Terminal::in(base_path())
                ->run('wget https://github.com/mozilla/DeepSpeech/releases/download/v0.8.2/deepspeech-0.8.2-models.pbmm');
        }

        if (file_exists(base_path() . "/deepspeech-0.8.2-models.scorer") === false) {
            Log::info("Downloading scorer");
            $download = Terminal::in(base_path())
                ->run('wget https://github.com/mozilla/DeepSpeech/releases/download/v0.8.2/deepspeech-0.8.2-models.scorer');
        }
 
        $ls_result = Terminal::in(base_path())
            ->run('ls');

        foreach ($ls_result as $line) {
            Log::info($line);
        }

        $transcribe_result = Terminal::in(base_path())
            ->run('deepspeech --model ./deepspeech-0.8.2-models.pbmm --scorer ./deepspeech-0.8.2-models.scorer --audio ' . $audio_tmp_file);

        foreach ($transcribe_result as $line) {
            Log::info($line);
        }

        Log::info("TRANSCRIBE DONE");
        
        //clean up the tmp file
        unlink($audio_tmp_file);
        
    }
}
