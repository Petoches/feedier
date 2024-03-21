<?php

namespace Tests\Feature\Jobs;

use App\Imports\FeedbackImport;
use App\Jobs\ImportExternalFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ImportExternalFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_import_external_feedback(): void
    {
        Excel::fake();
        Storage::fake('external');

        Str::createUuidsUsing(function () {
            return Uuid::fromString('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9');
        });

        (new ImportExternalFeedback())->handle();

        Storage::disk('external')->assertExists('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9.csv');
        Excel::assertImported('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9.csv', 'external', function(FeedbackImport $import) {
            return true;
        });
    }
}
