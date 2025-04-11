<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function user()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Người dùng chưa đăng nhập'
                ], 401);
            }

            return response()->json([
                'status' => 'success',
                'data' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            Log::error("Error in AuthController@user: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi lấy thông tin người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            Log::info("Register request: ", $request->all());
            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký thành công',
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error("Error in AuthController@register: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi trong quá trình đăng ký',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $errors = [];
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $errors['email'] = ['Email không tồn tại trong hệ thống, hãy kiểm tra lại'];
            }

            if ($user && !Hash::check($request->password, $user->password)) {
                $errors['password'] = ['Mật khẩu không chính xác, hãy kiểm tra lại'];
            }

            if (!empty($errors)) {
                throw ValidationException::withMessages($errors);
            }

            // Tạo token
            $token = $user
                ->createToken('auth_token'/* , expiresAt: now()->addMinutes(30) */)
                ->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $token,
                    'expires_at' => now()->addMinutes(30)->toDateTimeString()
                ]
            ]);
        } catch (ValidationException $e) {
            Log::error("Error in AuthController@login: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error("Error in AuthController@login: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi trong quá trình đăng nhập',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng xuất thành công'
            ]);
        } catch (Exception $e) {
            Log::error("Error in AuthController@logout: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi đăng xuất'
            ], 500);
        }
    }
}
