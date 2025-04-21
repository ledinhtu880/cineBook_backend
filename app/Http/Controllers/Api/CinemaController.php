<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CinemaRepository;
use App\Repositories\ShowtimeRepository;
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

            return response()->json([
                'status' => 'success',
                'data' => CinemaResource::collection($cinemas)
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Error in CinemaController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Quá trình tải rạp chiếu phim bị lỗi',
            ], 500);
        }
    }
    public function show(string $slug)
    {
        try {
            $cinema = $this->cinemaRepository->findBySlug($slug);

            if (!$cinema) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rạp chiếu phim không tồn tại'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => new CinemaResource($cinema)
            ]);
        } catch (\Exception $ex) {
            Log::error("Error in CinemaController@show: " . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình tải rạp chiếu phim'
            ], 500);
        }
    }
    public function getShowtimesByDate($slug, ShowtimeRepository $showtimeRepository)
    {
        try {
            $cinema = $this->cinemaRepository->findBySlug($slug);

            if (!$cinema) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Rạp chiếu phim không tồn tại'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $showtimeRepository->getGroupedByCinema($cinema->id)
            ]);
        } catch (\Exception $ex) {
            Log::error("Error in CinemaController@getShowtimesByDate: " . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình tải rạp suất chiếu'
            ], 500);
        }
    }
}
