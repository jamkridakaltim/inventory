<?php

namespace App\Http\Requests\ProcurementItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreProcurementItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [
        'request_item_id' => [
            'required',
            'exists:asset_request_items,id',
        ],

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