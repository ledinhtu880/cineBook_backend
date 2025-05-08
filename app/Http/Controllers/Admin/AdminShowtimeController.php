<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\ShowtimeResource;
use App\Repositories\ShowtimeRepository;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use Exception;

class AdminShowtimeController extends Controller
{
    protected $showtimeRepository;
    public function __construct(ShowtimeRepository $showtimeRepository)
    {
        $this->showtimeRepository = $showtimeRepository;
    }
    public function index()
    {
        try {
            $showtimes = $this->showtimeRepository->all();

            if ($showtimes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không có suất chiếu nào',
                ], 404);
            }
            return response()->json([
                'status' => 'success',
                'data' => ShowtimeResource::collection($showtimes),
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminShowtimeController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình tải suất chiếu xảy ra lỗi',
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
    public function show(string $id)
    {
        try {
            $showtime = $this->showtimeRepository->find($id);

            return response()->json($showtime);

        } catch (Exception $e) {
            Log::error('Error in AdminShowtimeController@show: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi lấy suất chiếu'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Showtime $showtime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Showtime $showtime)
    {
        //
    }
}
