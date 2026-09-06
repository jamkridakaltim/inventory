<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    $user = $this->route('user');

    return [
        'role_id' => 'required|exists:roles,id',

        'department_id' => 'nullable|exists:departments,id',

        'location_id' => 'nullable|exists:locations,id',

        'full_name' => 'required|string|max:150',

        'username' => 'required|string|max:100|unique:users,username,' . $user->id,

        'email' => 'required|email|max:150|unique:users,email,' . $user->id,

        'photo_url' => 'nullable|string|max:500',

        'is_active' => 'required|boolean',
    ];
}

    public function messages(): array
{
    return [
        'role_id.required' => 'Role wajib dipilih.',
        'role_id.exists' => 'Role tidak ditemukan.',

        'department_id.exists' => 'Department tidak ditemukan.',

        'location_id.exists' => 'Lokasi tidak ditemukan.',

        'full_name.required' => 'Nama lengkap wajib diisi.',

        'username.required' => 'Username wajib diisi.',
        'username.unique' => 'Username sudah digunakan.',

        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah digunakan.',
    ];
}
}