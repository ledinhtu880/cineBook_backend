<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'title' => $this->title,
            'duration' => $this->formatted_duration,
            'release_date' => $this->release_date,
            'poster_url' => $this->poster_url,
            'trailer_url' => $this->trailer_url,
            'age_rating' => $this->age_rating,
        ];
    }
}
