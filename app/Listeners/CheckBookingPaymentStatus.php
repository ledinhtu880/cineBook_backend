<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use PayOS\PayOS;

class CheckBookingPaymentStatus implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $delay = 300;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        $bookingId = $event->id;

        $booking = \App\Models\Booking::find($bookingId);

        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $payOS = new PayOS($clientId, $apiKey, $checksumKey);

        try {
            $paymentInfo = $payOS->getPaymentLinkInformation($booking->id);

            if (isset($paymentInfo['status']) && $paymentInfo['status'] === 'PAID') {
                $booking->payment_status = 'paid';
                $booking->save();
            } else {
                $booking->delete();
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment: ' . $e->getMessage());

            $booking->payment_status = 'failed';
            $booking->save();
        }
    }
}