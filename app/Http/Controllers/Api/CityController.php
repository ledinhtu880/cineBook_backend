<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CityRepository;
use App\Http\Resources\CityResource;
use Illuminate\Support\Facades\Log;
use Exception;

class CityController extends Controller
{
    protected $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        try {
            $cities = $this->cityRepository->all();

            return response()->json([
                'status' => 'success',
                'data' => (CityResource::collection($cities))
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in CityController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Quá trình tải thành phố bị lỗi',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
