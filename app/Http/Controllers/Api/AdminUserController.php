<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

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
                'message' => 'Failed to fetch users',
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
                'message' => 'Failed to fetch user',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
