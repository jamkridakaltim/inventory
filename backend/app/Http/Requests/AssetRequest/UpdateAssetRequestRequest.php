<?php

namespace App\Http\Requests\AssetRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequestRequest extends FormRequest
{
    /**
     * Menentukan apakah user boleh melakukan request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [
            'request_date' => [
                'required',
                'date',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'recipient_name' => [
                'required',
                'string',
                'max:200',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}