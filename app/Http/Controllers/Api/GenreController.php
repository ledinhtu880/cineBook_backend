<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\GenreResource;
use App\Repositories\GenreRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class GenreController extends Controller
{
    protected $repository;
    public function __construct(GenreRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index()
    {
        try {
            $data = $this->repository->all();

            return response()->json([
                'status' => 'success',
                'data' => GenreResource::collection($data)
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GenreController@index: ' . $e->getMessage());
            return response()->json([
                'message' => 'Có lỗi xảy ra trong quá trình lấy danh sách thể loại phim',
            ], 500);
        }

    }
}
