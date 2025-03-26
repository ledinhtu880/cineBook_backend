<?php

return [
    'required' => 'Trường :attribute là bắt buộc.',
    'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'max' => [
        'string' => 'Trường :attribute không được lớn hơn :max ký tự.',
    ],
    'min' => [
        'string' => 'Trường :attribute phải có ít nhất :min ký tự.',
    ],
    'unique' => 'Trường :attribute đã tồn tại trong hệ thống.',
    'same' => 'Trường :attribute và :other phải giống nhau.',

    'attributes' => [
        'email' => 'email',
        'password' => 'mật khẩu',
        'password_confirmation' => 'xác nhận mật khẩu',
        'first_name' => 'tên',
        'last_name' => 'họ',
    ],
];
