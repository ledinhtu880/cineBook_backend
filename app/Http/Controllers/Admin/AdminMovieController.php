<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Validation\ValidationException;
use App\Repositories\MovieRepository;
use App\Http\Resources\MovieResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Movie;
use Exception;
use Illuminate\Support\Str;

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
                'message' => 'Failed to fetch movies',
            ], 500);
        }
    }
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'duration' => 'required|integer',
                'release_date' => 'required|date',
                'description' => 'required|string',
                'poster_url' => 'required',
                'trailer_url' => 'nullable|string|max:255',
                'age_rating' => 'required|string|max:10',
            ]);

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
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@store: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create movies',
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
                    'message' => 'Movie not found',
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
                'message' => 'Failed to fetch movie',
            ], 500);
        }
    }
    public function update(Request $request, Movie $movie)
    {
        try {
            $keepExistingPoster = $request->input('keep_existing_poster');

            $rules = [
                'title' => 'required|string|max:255',
                'duration' => 'required|integer',
                'release_date' => 'required|date',
                'description' => 'required|string',
                'trailer_url' => 'nullable|string|max:255',
                'age_rating' => 'required|string|max:10',
            ];

            $validatedData = $request->validate($rules);

            // Nếu giữ poster cũ, loại bỏ trường poster_url khỏi dữ liệu cập nhật
            if ($keepExistingPoster) {
                // Không cập nhật trường poster_url
                unset($validatedData['poster_url']);
            } else if ($request->hasFile('poster_url')) {
                $file = $request->file('poster_url');
                $fileName = time() . '_' . Str::slug($validatedData['title']) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('posters', $fileName, 'public');
                $validatedData['poster_url'] = $path;
            }

            // Cập nhật movie với dữ liệu đã xác thực
            $movie->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật phim thành công'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@update: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update movies',
            ], 500);
        }
    }
    public function destroy(string $id)
    {
        try {
            Log::info('Delete movie with ID: ' . $id);
            $movie = $this->movieRepository->find($id);
            if (!$movie) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Movie not found',
                ], 404);
            }
            $this->movieRepository->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Xóa phim thành công'
            ], 201);
        } catch (Exception $ex) {
            Log::error('Error in AdminMovieController@destroy: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete movies',
            ], 500);
        }
    }
}
