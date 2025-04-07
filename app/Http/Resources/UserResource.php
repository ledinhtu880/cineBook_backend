<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $withFullInfo = false;

    public function withFullInfo(): self
    {
        $this->withFullInfo = true;
        return $this;
    }

    public function toArray(Request $request): array
    {
        // Basic info luôn được trả về
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->city->name,
            'role' => $this->role,
            'string_role' => $this->formatted_role,
        ];

        // Additional info khi cần
        if ($this->withFullInfo) {
            $data = array_merge($data, [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            ]);
        }

        return $data;
    }
}
