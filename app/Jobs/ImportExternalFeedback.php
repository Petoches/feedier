<?php

namespace App\Jobs;

use App\Services\ImportFeedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportExternalFeedback implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = config('services.s3.feedback');

        $service = new ImportFeedback();

        $import = $service->url($url)->import();

        if(!$import) {
           $this->fail('Something went wrong.');
        }
    }
}
