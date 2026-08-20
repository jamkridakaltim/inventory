<?php

namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_request_id' => [
                'required',
                'exists:asset_requests,id',
            ],

            'procurement_date' => [
                'required',
                'date',
            ],

            'vendor_name' => [
                'nullable',
                'string',
                'max:200',
            ],

            'status' => [
                'nullable',
                'in:draft,ordered,received,cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}