<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\MovieRepository;
use App\Http\Resources\MovieResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Movie;

class AdminMovieController extends Controller
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
            Log::error('Error in AdminMovieController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch movies',
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
