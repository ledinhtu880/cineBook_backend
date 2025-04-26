<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\Booking\BookingStoreRequest as StoreRequest;
use App\Repositories\BookingRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BookingController extends Controller
{
    protected $bookingRepository;
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            $this->bookingRepository->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Đặt vé thành công',
            ], 201);
        } catch (Exception $e) {
            Log::error('Error in BookingController@store: ' . $e->getMessage());
            return response()->json(['error' => 'Có lỗi xảy ra khi đặt vé.'], 500);
        }
    }
    public function show(string $id)
    {
    }
    public function update(Request $request, string $id)
    {
    }
    public function destroy(string $id)
    {
    }
}
