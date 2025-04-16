<?php

namespace App\Http\Requests\Admin\Movie;

use Illuminate\Foundation\Http\FormRequest;

class MovieUpdateRequest extends FormRequest
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
            'trailer_url' => 'nullable|string|max:255',
            'age_rating' => 'required|string|max:10',
            'keep_existing_poster' => 'boolean',
            'poster_url' => 'required_if:keep_existing_poster,false|file|image|max:2048'
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

            'poster_url.required_if' => 'Vui lòng chọn poster mới khi không giữ poster cũ',
            'poster_url.file' => 'Poster phải là một tệp',
            'poster_url.image' => 'Poster phải là hình ảnh',
            'poster_url.max' => 'Kích thước poster không được vượt quá 2MB',

            'trailer_url.string' => 'URL trailer phải là chuỗi ký tự',
            'trailer_url.max' => 'URL trailer không được vượt quá 255 ký tự',

            'age_rating.required' => 'Giới hạn độ tuổi không được để trống',
            'age_rating.string' => 'Giới hạn độ tuổi phải là chuỗi ký tự',
            'age_rating.max' => 'Giới hạn độ tuổi không được vượt quá 10 ký tự',
        ];
    }
}
