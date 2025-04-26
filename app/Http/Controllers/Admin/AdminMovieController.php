<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Movie\MovieStoreRequest;
use App\Http\Requests\Admin\Movie\MovieUpdateRequest;
use App\Repositories\MovieRepository;
use App\Http\Resources\MovieResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Movie;
use Exception;

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
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình tải phim xảy ra lỗi',
            ], 500);
        }
    }
    public function store(MovieStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Kiểm tra và xử lý file
            if ($request->hasFile('poster_url')) {
                $file = $request->file('poster_url');

                $fileName = time() . '_' . Str::slug($validatedData['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posters', $fileName, 'public');
                $validatedData['poster_url'] = $path;
            }

            $this->movieRepository->create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Tạo phim thành công'
            ], 201);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@store: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình thêm phim xảy ra lỗi',
            ], 500);
        }
    }
    public function show(Movie $movie)
    {
        try {
            $movie = $this->movieRepository->find($movie->id);
            if (!$movie) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phim',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => new MovieResource($movie)
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@show: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình lấy phim xảy ra lỗi',
            ], 500);
        }
    }
    public function update(MovieUpdateRequest $request, Movie $movie)
    {
        try {
            $validatedData = $request->validated();
            $keepExistingPoster = $request->input('keep_existing_poster', false);

            if ($keepExistingPoster) {
                unset($validatedData['poster_url']);
            } else if ($request->hasFile('poster_url')) {
                $file = $request->file('poster_url');
                $fileName = time() . '_' . Str::slug($validatedData['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posters', $fileName, 'public');
                $validatedData['poster_url'] = $path;
            }

            $this->movieRepository->update($movie->id, $validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật phim thành công'
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@update: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình cập nhật phim xảy ra lỗi',
            ], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            $movie = $this->movieRepository->find($id);
            if (!$movie) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy phòng phim',
                ], 404);
            }
            $this->movieRepository->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa phim thành công'
            ], 204);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@destroy: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Quá trình xóa phim xảy ra lỗi',
            ], 500);
        }
    }
}
