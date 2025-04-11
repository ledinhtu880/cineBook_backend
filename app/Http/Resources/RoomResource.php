<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    protected $withFullInfo = false;

    public function withFullInfo(): self
    {
        $this->withFullInfo = true;
        return $this;
    }
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'cinema_id' => $this->cinema_id,
            'cinema_name' => $this->cinema->name,
            'name' => $this->name,
            'seat_rows' => $this->seat_rows,
            'seat_columns' => $this->seat_columns,
            'sweetbox_rows' => $this->sweetbox_rows,
        ];

        if ($this->withFullInfo) {
            $data = array_merge($data, [
                'seats' => $this->seats,
            ]);
        }

        return $data;
    }
}
