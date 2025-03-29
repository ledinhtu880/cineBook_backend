<?php

namespace App\Http\Controllers;

use App\Repositories\MovieRepository;
use App\Http\Resources\MovieResource;
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

            return response()->json([
                'status' => 'success',
                'data' => MovieResource::collection($movies),
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Error fetching movies: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch movies',
            ], 500);
        }
    }
}
