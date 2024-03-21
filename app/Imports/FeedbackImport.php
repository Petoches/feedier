<?php

namespace App\Imports;

use App\Enums\FeedbackSource;
use App\Models\Feedback;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FeedbackImport implements ToModel, withHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Feedback([
            'content' => $row['reviews_content'],
            'rating' => $row['rating'],
            'address' => $row['address'],
            'appartments' => $row['appartments'],
            'source' => FeedbackSource::External->name,
            'external_source' => $row['source'],
            'created_at' => $row['start_date'],
            'updated_at' => $row['start_date'],
        ]);
    }
}
