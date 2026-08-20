<?php

namespace App\Http\Requests\AssetRequestItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequestItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'item_name' => [
                'required',
                'string',
                'max:200',
            ],

            'specification' => [
                'nullable',
                'string',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'unit_name' => [
                'required',
                'string',
                'max:50',
            ],

            'requested_amount' => [
                'required',
                'numeric',
                'min:0',
            ],

        ];
    }
}