<?php

namespace App\Http\Requests\User\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'showtime_id' => 'required|integer|exists:showtimes,id',
            'seats' => 'required|array|min:1',
            'seats.*.id' => 'integer|exists:seats,id',
            'seats.*.price' => 'required|decimal:0,2|min:1',
            'combos' => 'nullable|array',
            'combos.*.id' => 'required|integer|exists:product_combos,id',
            'combos.*.quantity' => 'required|integer|min:1',
            'combos.*.price' => 'required|decimal:0,2|min:1',
            'payment_method' => 'required|string|in:cash,credit_card,bank_transfer,e_wallet',
            'total_amount' => 'required|numeric|min:0',
            'booking_time' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'showtime_id.required' => 'Vui lòng chọn suất chiếu',
            'showtime_id.integer' => 'Mã suất chiếu không hợp lệ',
            'showtime_id.exists' => 'Suất chiếu không tồn tại',

            'seats.required' => 'Vui lòng chọn ít nhất một ghế',
            'seats.array' => 'Danh sách ghế không hợp lệ',
            'seats.min' => 'Vui lòng chọn ít nhất một ghế',
            'seats.*.id.integer' => 'Mã ghế không hợp lệ',
            'seats.*.id.exists' => 'Ghế không tồn tại',
            'seats.*.price.required' => 'Giá tiền của ghế',
            'seats.*.price.decimal' => 'Giá tiền của ghế không hợp lệ',
            'seats.*.price.min' => 'Giá tiền của ghế phải lớn hơn 0',

            'combos.array' => 'Danh sách combo không hợp lệ',
            'combos.*.id.required' => 'Mã combo không được để trống',
            'combos.*.id.integer' => 'Mã combo không hợp lệ',
            'combos.*.id.exists' => 'Combo không tồn tại',
            'combos.*.quantity.required' => 'Số lượng combo không được để trống',
            'combos.*.quantity.integer' => 'Số lượng combo phải là số nguyên',
            'combos.*.quantity.min' => 'Số lượng combo phải lớn hơn 0',
            'combos.*.price.required' => 'Giá tiền của combo không được để trống',
            'combos.*.price.decimal' => 'Giá tiền của combo không hợp lệ',
            'combos.*.price.min' => 'Giá tiền của combo phải lớn hơn 0',

            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.string' => 'Phương thức thanh toán không hợp lệ',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',

            'total_amount.required' => 'Tổng tiền không được để trống',
            'total_amount.numeric' => 'Tổng tiền phải là số',
            'total_amount.min' => 'Tổng tiền không hợp lệ',

            'booking_time.required' => 'Thời gian đặt không được để trống',
            'booking_time.date' => 'Thời gian đặt không hợp lệ',
        ];
    }
}
