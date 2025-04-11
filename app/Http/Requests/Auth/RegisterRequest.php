<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'city_id' => 'nullable|integer|exists:cities,id',
            'phone' => 'string|max:255|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
            ],
            'password_confirmation' => 'required|same:password'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Vui lòng nhập họ',
            'first_name.max' => 'Họ không được vượt quá :max ký tự',
            'last_name.required' => 'Vui lòng nhập tên',
            'last_name.max' => 'Tên không được vượt quá :max ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'phone.unique' => 'Số điện thoại đã tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu',
            'password_confirmation.same' => 'Xác nhận mật khẩu không khớp'
        ];
    }
}
