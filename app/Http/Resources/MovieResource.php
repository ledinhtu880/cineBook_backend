<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            'duration' => $this->duration,
            'genres' => $this->genres_list,
            'release_date' => $this->release_date,
            'poster_url' => "http://localhost:8000/storage/" . $this->poster_url,
            'trailer_url' => $this->trailer_url,
            'age_rating' => $this->age_rating,
        ];
    }
}
