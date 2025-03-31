<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CinemaRepository;
use App\Http\Resources\CinemaResource;
use Illuminate\Support\Facades\Log;

class CinemaController extends Controller
{
    protected $cinemaRepository;

    public function __construct(CinemaRepository $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }

    public function index()
    {
        try {
            $cinemas = $this->cinemaRepository->all();

            return response()->json(CinemaResource::collection($cinemas), 200);
        } catch (\Exception $ex) {
            Log::error('Error in CinemaController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Failed to fetch movies',
            ], 500);
        }
    }
}
