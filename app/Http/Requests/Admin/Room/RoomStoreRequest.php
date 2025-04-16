<?php

namespace App\Http\Requests\Admin\Room;

use Illuminate\Foundation\Http\FormRequest;

class RoomStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'seat_rows' => 'required|integer|min:1',
            'seat_columns' => 'required|integer|min:1',
            'sweetbox_rows' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên phòng chiếu không được để trống',
            'name.string' => 'Tên phòng chiếu phải là chuỗi ký tự',
            'name.max' => 'Tên phòng chiếu không được vượt quá 255 ký tự',

            'seat_rows.required' => 'Số hàng ghế không được để trống',
            'seat_rows.integer' => 'Số hàng ghế phải là số nguyên',
            'seat_rows.min' => 'Số hàng ghế phải lớn hơn 0',

            'seat_columns.required' => 'Số cột ghế không được để trống',
            'seat_columns.integer' => 'Số cột ghế phải là số nguyên',
            'seat_columns.min' => 'Số cột ghế phải lớn hơn 0',

            'sweetbox_rows.integer' => 'Số hàng ghế đôi phải là số nguyên',
            'sweetbox_rows.min' => 'Số hàng ghế đôi không được âm',
        ];
    }
}
