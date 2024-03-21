<?php

namespace Tests\Feature\Services;

use App\Imports\FeedbackImport;
use App\Services\ImportFeedback;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ImportFeedbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_store_file_from_url(): void
    {
        Storage::fake('external');

        Str::createUuidsUsing(function () {
            return Uuid::fromString('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9');
        });

        $url = config('services.s3.feedback');

        $service = new ImportFeedback();

        $service->url($url);

        Storage::disk('external')->assertExists('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9.csv');
    }

    public function test_it_import_file(): void
    {
        Excel::fake();

        Storage::fake('external');

        Str::createUuidsUsing(function () {
            return Uuid::fromString('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9');
        });

        $url = config('services.s3.feedback');

        $service = new ImportFeedback();

        $service->url($url)->import();

        Excel::assertImported('eadbfeac-5258-45c2-bab7-ccb9b5ef74f9.csv', 'external', function(FeedbackImport $import) {
            return true;
        });
    }
}
