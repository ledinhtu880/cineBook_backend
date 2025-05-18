<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'movie' => $this->showtime->movie->title,
            'cinema' => $this->showtime->cinema->name,
            'date' => $this->showtime->date,
            'time' => $this->showtime->start_time_formatted,
            'seats' => $this->seats,
            'total' => $this->total_price_formatted,
            'status' => $this->payment_status == 'paid' ? 'Hoàn thành' : 'Đang chờ',
            'posterUrl' => $this->showtime->movie->poster_url_label,
        ];
    }
}
