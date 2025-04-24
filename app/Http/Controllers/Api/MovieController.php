<?php

namespace App\Http\Controllers\Api;

use App\Repositories\MovieRepository;
use App\Repositories\ShowtimeRepository;
use App\Http\Resources\MovieResource;
use App\Http\Controllers\Controller;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class MovieController extends Controller
{
    protected $movieRepository;
    protected $showtimeRepository;

    public function __construct(MovieRepository $movieRepository, ShowtimeRepository $showtimeRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->showtimeRepository = $showtimeRepository;
    }

    public function index(Request $request)
    {
        try {
            $params = ApiHelper::getRequestParams($request);
            $movies = $this->movieRepository->all($params);

            return response()->json([
                'status' => 'success',
                'data' => MovieResource::collection($movies)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in MovieController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình phim',
            ], 500);
        }
    }
    public function nowShowing(Request $request)
    {
        try {
            $params = ApiHelper::getRequestParams($request);
            $movies = $this->movieRepository->getNowShowing($params);
            return response()->json([
                'status' => 'success',
                'data' => MovieResource::collection($movies)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in MovieController@nowShowing: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình phim đang chiếu',
            ], 500);
        }
    }

    public function comingSoon(Request $request)
    {
        try {
            $params = ApiHelper::getRequestParams($request);
            $movies = $this->movieRepository->getComingSoon($params);
            return response()->json([
                'status' => 'success',
                'data' => MovieResource::collection($movies)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in MovieController@comingSoon: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình phim sắp chiếu',
            ], 500);
        }
    }
    public function show(string $slug)
    {
        try {
            $movie = $this->movieRepository->findBySlug($slug);

            if (!$movie) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phim'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => new MovieResource($movie)
            ]);
        } catch (Exception $e) {
            Log::error('Error in MovieController@show: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi lấy thông tin phim'
            ], 500);
        }
    }
    public function getShowtimesById(string $id)
    {
        try {
            $showtimes = $this->showtimeRepository->getByMovie($id);

            return response()->json(['data' => $showtimes]);

        } catch (Exception $e) {
            Log::error('Error in MovieController@getShowtimesById: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi lấy suất chiếu'
            ], 500);
        }
    }
}
