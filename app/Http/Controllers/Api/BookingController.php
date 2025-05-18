<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\Booking\BookingStoreRequest as StoreRequest;
use App\Repositories\BookingRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Mail\BookingConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use PayOS\PayOS;

class BookingController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;

        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');

        $this->payOS = new PayOS($clientId, $apiKey, $checksumKey);
    }
    public function index()
    {
        try {
            $userId = Auth::user()->id;
            $bookings = $this->bookingRepository->getByUser($userId);

            return response()->json([
                'status' => 'success',
                'data' => BookingResource::collection($bookings),
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in BookingController@getByUser: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra khi lấy danh sách vé.'], 500);
        }
    }
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            $totalAmount = $request["total_amount"];
            $returnUrl = $request["returnUrl"];
            $cancelUrl = $request["cancelUrl"];

            $booking = $this->bookingRepository->create($data);

            $timestamp = (string) microtime(true);
            $timestamp = str_replace('.', '', $timestamp);
            $orderCode = substr($timestamp, -6) . $booking->id;

            $checkoutData = [
                "orderCode" => (int) $orderCode,
                "amount" => $totalAmount,
                "description" => "Thanh toán vé xem phim",
                "returnUrl" => $returnUrl,
                "cancelUrl" => $cancelUrl,
                "expiredAt" => time() + 5 * 60,
            ];

            $response = $this->payOS->createPaymentLink($checkoutData);

            event(new \App\Events\BookingCreated($booking->id));

            return response()->json([
                'status' => 'success',
                'message' => 'Đặt vé thành công',
                'checkoutUrl' => $response['checkoutUrl'],
            ], 201);
        } catch (Exception $e) {
            Log::error('Error in BookingController@store: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra khi đặt vé.'], 500);
        }
    }
    public function update(string $id)
    {
        try {
            $booking = $this->bookingRepository->find($id);

            if (!$booking) {
                return response()->json(['status' => 'error', 'message' => 'Không tìm thấy vé.'], 404);
            }

            $this->bookingRepository->update($id);

            Mail::to($booking->user->email)->send(new BookingConfirmation($booking));

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật trạng thái thanh toán thành công.',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in BookingController@update: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Có lỗi xảy ra khi cập nhật vé.'], 500);
        }
    }
}
