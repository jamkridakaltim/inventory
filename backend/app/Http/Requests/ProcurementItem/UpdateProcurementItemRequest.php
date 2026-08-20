<?php

namespace App\Http\Requests\ProcurementItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProcurementItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'actual_quantity' => [
    'nullable',
    'integer',
    'min:1',
],

'actual_amount' => [
    'nullable',
    'numeric',
    'min:0',
],

            'received_at' => [
                'nullable',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}