<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index()
    {
        try {
            $users = $this->userRepository->all();

            return response()->json([
                'status' => 'success',
                'data' => UserResource::collection($users),
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Error in AdminUserController@index: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình tải người dùng',
            ], 500);
        }
    }
    public function show(string $id)
    {
        try {
            $user = $this->userRepository->find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => (new UserResource($user))->withFullInfo()
            ], 200);
        } catch (\Exception $ex) {
            Log::error('Error in AdminUserController@show: ' . $ex->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình tải người dùng',
            ], 500);
        }
    }
}
