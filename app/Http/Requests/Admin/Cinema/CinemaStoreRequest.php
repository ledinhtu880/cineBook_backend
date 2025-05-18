<?php

namespace App\Http\Requests\Admin\Cinema;

use Illuminate\Foundation\Http\FormRequest;

class CinemaStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city_id' => 'required|integer',
            'phone' => 'required|string',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Tên rạp chiếu phim không được để trống',
            'name.string' => 'Tên rạp chiếu phim phải là chuỗi ký tự',
            'name.max' => 'Tên rạp chiếu phim không được vượt quá 255 ký tự',

            'address.required' => 'Địa chỉ không được để trống',
            'address.string' => 'Địa chỉ phải là chuỗi ký tự',

            'city_id.required' => 'Vui lòng chọn thành phố',
            'city_id.integer' => 'Mã thành phố không hợp lệ',

            'phone.required' => 'Số điện thoại không được để trống',
            'phone.string' => 'Số điện thoại phải là chuỗi ký tự',
        ];
    }
}
