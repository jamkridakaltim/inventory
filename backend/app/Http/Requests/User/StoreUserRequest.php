<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',

            'full_name' => 'required|string|max:150',

            'username' => 'required|string|max:100|unique:users,username',

            'email' => 'required|email|max:150|unique:users,email',

            // password dari frontend
            'password' => 'required|string|min:8',

            'photo_url' => 'nullable|string|max:500',

            'is_active' => 'boolean',
        ];
    }
}