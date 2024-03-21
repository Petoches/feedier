<?php

namespace App\Services;

use App\Imports\FeedbackImport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportFeedback
{

    protected string $fileName;

    public function url($url): self
    {
        $file = file_get_contents($url);

        $this->fileName = Str::uuid().'.csv';

        Storage::disk('external')->put($this->fileName, $file);

        return $this;
    }

    public function import(): bool
    {
        try {
            Excel::import(new FeedbackImport(), $this->fileName, 'external');
        } catch(\Exception $exception) {
            Log::warning('something went wrong while importing file : '.$this->fileName);
            return false;
        }
        return true;
    }
}
