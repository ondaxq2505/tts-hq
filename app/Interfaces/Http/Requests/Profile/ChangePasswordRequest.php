<?php

namespace App\Interfaces\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'old_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                'current_password', 
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'different:old_password',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                'confirmed' 
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'old_password.current_password' => 'Mật khẩu cũ không đúng',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.different' => 'Mật khẩu mới trùng với mật khẩu cũ. Vui lòng nhập lại',
            'password.regex' => 'Mật khẩu mới phải có ít nhất 8 ký tự, bao gồm cả chữ hoa, chữ thường, số, ký tự đặc biệt.',
            'password.confirmed' => 'Mật khẩu xác nhận không đúng.',
        ];
    }
}
