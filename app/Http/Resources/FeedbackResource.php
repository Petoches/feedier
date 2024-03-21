<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->user_id ? $this->user->email : $this->email,
            'content' => Str::limit($this->content, 50),
            'source' => $this->source,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
