<?php

namespace App\Http\Controllers\Api;

use App\Repositories\MovieRepository;
use App\Http\Resources\MovieResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class MovieController extends Controller
{
    protected $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function index()
    {
        try {
            $movies = $this->movieRepository->all();

            return response()->json(MovieResource::collection($movies), 200);
        } catch (\Exception $ex) {
            Log::error('Error in MovieController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Failed to fetch movies',
            ], 500);
        }
    }
    public function nowShowing()
    {
        try {
            $movies = $this->movieRepository->getNowShowing();
            return response()->json(MovieResource::collection($movies), 200);
        } catch (\Exception $ex) {
            Log::error('Error in MovieController@nowShowing: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Failed to fetch now showing movies',
            ], 500);
        }
    }

    public function comingSoon()
    {
        try {
            $movies = $this->movieRepository->getComingSoon();
            return response()->json(MovieResource::collection($movies), 200);
        } catch (\Exception $ex) {
            Log::error('Error in MovieController@comingSoon: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Failed to fetch coming soon movies',
            ], 500);
        }
    }
}
