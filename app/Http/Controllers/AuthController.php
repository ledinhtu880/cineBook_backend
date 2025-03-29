<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'password_confirmation' => 'required|same:password'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng ký thành công',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
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

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $errors = [];
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $errors['email'] = ['Email không tồn tại trong hệ thống, hãy kiểm tra lại'];
            }

            if ($user && !Hash::check($request->password, $user->password)) {
                $errors['password'] = ['Mật khẩu không chính xác, hãy kiểm tra lại'];
            }

            if (!empty($errors)) {
                Log::info("Du lieu: ", [$errors]);
                throw ValidationException::withMessages($errors);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => $user,
                    'token' => $token
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
            $request->user()->currentAccessToken()->delete();

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
