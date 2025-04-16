<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Repositories\RoomRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Room;
use Exception;

class AdminRoomController extends Controller
{
    protected $roomRepository;
    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }
    public function index()
    {
        try {
            $rooms = $this->roomRepository->all();

            return response()->json([
                'status' => 'success',
                'data' => RoomResource::collection($rooms)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình tải rạp xảy ra lỗi',
            ], 500);
        }
    }
    public function show(Room $room)
    {
        try {
            $room = $this->roomRepository->find($room->id);
            if (!$room) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phòng chiếu',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => (new RoomResource($room))->withFullInfo()
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@show: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình lấy phim xảy ra lỗi',
            ], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $room = $this->roomRepository->find($id);
            if (!$room) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phòng chiếu',
                ], 404);
            }

            $this->roomRepository->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa phòng chiếu công'
            ], 201);
        } catch (Exception $ex) {
            Log::error('Error in AdminRoomController@destroy: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình xóa phim xảy ra lỗi',
            ], 500);
        }
    }
}
