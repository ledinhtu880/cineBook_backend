<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function getPaymentLinkInfoOfOrder(string $id)
    {
        try {
            $response = $this->payOS->getPaymentLinkInformation($id);
            return response()->json($response["id"]);
        } catch (\Throwable $th) {
            Log::error("Error in PaymentController@getPaymentLinkInfoOfOrder: " . $th->getMessage());
            return $this->handleException($th);
        }
    }

    public function cancelPaymentLinkOfOrder(Request $request, string $id)
    {
        $orderCode = 123456;
        $reason = "Hủy đơn hàng";

        try {
            $response = $this->payOS->cancelPaymentLink($orderCode, $reason);
            return response()->json([
                "error" => 0,
                "message" => "Success",
                "data" => $response["data"]
            ]);
        } catch (\Throwable $th) {
            return $this->handleException($th);
        }
    }
}
