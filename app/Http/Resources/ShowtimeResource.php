<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowtimeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'movie' => [
                'id' => $this->movie->id,
                'title' => $this->movie->title,
                'duration' => $this->movie->duration,
                'poster' => "http://localhost:8000/" . $this->movie->poster_url,
            ],
            'room' => [
                'id' => $this->room->id,
                'name' => $this->room->name,
                'cinema_name' => $this->room->cinema->name,
            ],
            'time' => [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ],
        ];
    }
}
