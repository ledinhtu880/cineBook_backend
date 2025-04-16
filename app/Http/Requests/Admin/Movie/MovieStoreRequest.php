<?php

namespace App\Http\Requests\Admin\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'duration' => 'required|integer',
            'release_date' => 'required|date',
            'description' => 'required|string',
            'poster_url' => 'required',
            'trailer_url' => 'nullable|string|max:255',
            'age_rating' => 'required|string|max:10',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'Tên phim không được để trống',
            'title.string' => 'Tên phim phải là chuỗi ký tự',
            'title.max' => 'Tên phim không được vượt quá 255 ký tự',

            'duration.required' => 'Thời lượng phim không được để trống',
            'duration.integer' => 'Thời lượng phim phải là số nguyên',

            'release_date.required' => 'Ngày khởi chiếu không được để trống',
            'release_date.date' => 'Ngày khởi chiếu không đúng định dạng',

            'description.required' => 'Mô tả phim không được để trống',
            'description.string' => 'Mô tả phim phải là chuỗi ký tự',

            'poster_url.required' => 'Poster phim không được để trống',

            'trailer_url.string' => 'URL trailer phải là chuỗi ký tự',
            'trailer_url.max' => 'URL trailer không được vượt quá 255 ký tự',

            'age_rating.required' => 'Giới hạn độ tuổi không được để trống',
            'age_rating.string' => 'Giới hạn độ tuổi phải là chuỗi ký tự',
            'age_rating.max' => 'Giới hạn độ tuổi không được vượt quá 10 ký tự',
        ];
    }
}
