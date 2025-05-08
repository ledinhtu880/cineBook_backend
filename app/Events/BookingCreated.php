<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
