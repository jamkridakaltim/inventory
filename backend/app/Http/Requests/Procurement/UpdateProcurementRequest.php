<?php

namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProcurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
                'required',
                'in:draft,ordered,received,cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}