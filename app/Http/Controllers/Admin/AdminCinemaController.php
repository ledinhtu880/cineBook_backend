<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Repositories\CinemaRepository;
use App\Http\Controllers\Controller;
use App\Http\Resources\CinemaResource;
use App\Models\Cinema;
use Exception;

class AdminCinemaController extends Controller
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
                'data' => CinemaResource::collection($cinemas),
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình tải rạp xảy ra lỗi',
            ], 500);
        }
    }
    public function getRooms(string $cinemaId)
    {
        try {
            $rooms = $this->cinemaRepository->getRooms($cinemaId);

            return response()->json([
                'status' => 'success',
                'data' => $rooms,
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@getRooms: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình tải rạp xảy ra lỗi',
            ], 500);
        }
    }
    public function storeRoom(string $cinemaId, Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'seat_rows' => 'required|integer',
                'seat_columns' => 'required|integer',
                'sweetbox_rows' => 'nullable|integer',
            ]);

            Log::info('Creating room with data: ', $validatedData);
            $this->cinemaRepository->createRoom($cinemaId, $validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo phòng chiếu thành công'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@storeRoom: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình thêm phòng chiếu xảy ra lỗi',
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'city_id' => 'required|integer',
                'phone' => 'required|string',
            ]);

            $this->cinemaRepository->create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo rạp chiếu phim thành công'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@store: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình thêm rạp chiếu phim xảy ra lỗi',
            ], 500);
        }
    }
    public function show(Cinema $cinema)
    {
        try {
            $cinema = $this->cinemaRepository->find($cinema->id);
            if (!$cinema) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Movie not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => new CinemaResource($cinema)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@show: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình lấy rạp chiếu phim xảy ra lỗi',
            ], 500);
        }
    }
    public function update(Request $request, Cinema $cinema)
    {
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'city_id' => 'required|integer',
                'phone' => 'required|string',
            ];

            $validatedData = $request->validate($rules);

            // Cập nhật movie với dữ liệu đã xác thực
            $cinema->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật rạp chiếu phim thành công'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@update: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình cập nhật rạp chiếu phim xảy ra lỗi',
            ], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $cinema = $this->cinemaRepository->find($id);
            if (!$cinema) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Movie not found',
                ], 404);
            }
            $this->cinemaRepository->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa rạp chiếu phim thành công'
            ], 201);
        } catch (Exception $ex) {
            Log::error('Error in AdminCinemaController@destroy: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình xóa rạp chiếu phim xảy ra lỗi',
            ], 500);
        }
    }
}
