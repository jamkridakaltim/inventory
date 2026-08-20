<?php

namespace App\Http\Requests\AssetRequest;

use Illuminate\Foundation\Http\FormRequest;

class ApproveAssetRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}