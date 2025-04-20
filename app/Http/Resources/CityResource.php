<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Basic info luôn được trả về
        $data = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($request->query('with_cinemas')) {
            $data = array_merge($data, [
                'cinemas' => CinemaResource::collection($this->cinemas),
            ]);
        }

        return $data;
    }
}
