<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'email' => [
                'required',
            ],
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
            ],
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => "Mật khẩu phải có ít nhất 8 ký tự",
            'password.numbers' => "Mật khẩu phải có ít nhất 1 ký tự số",
            'password.letters' => "Mật khẩu phải có ít nhất 1 ký tự chữ cái",
            'password.mixed_case' => "Mật khẩu phải có ít nhất 1 ký tự viết hoa và 1 ký tự viết thường"
        ];
    }
}
