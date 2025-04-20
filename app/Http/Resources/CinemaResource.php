<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CinemaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
            'opening_hours' => $this->opening_hours,
            'slug' => $this->slug,
            'image' => "http://localhost:8000/" . $this->image
        ];

        if ($request->query('get-city')) {
            $data = array_merge($data, [
                'city' => new CityResource($this->city),
            ]);
        }

        return $data;
    }
}
