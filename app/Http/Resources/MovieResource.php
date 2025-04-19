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
            'duration' => $this->duration,
            'duration_label' => $this->duration_label,
            'description' => $this->description,
            'genres' => $this->genres_list,
            'release_date' => $this->release_date,
            'release_date_label' => $this->release_date_label,
            'banner_url' => "http://localhost:8000/" . $this->banner_url,
            'poster_url' => "http://localhost:8000/" . $this->poster_url,
            'trailer_url' => $this->trailer_url,
            'age_rating' => $this->age_rating,
            'rating' => $this->rating,
            'country' => $this->country,
            'slug' => $this->slug
        ];
    }
}
