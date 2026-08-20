<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'asset_name' => [
                'required',
                'string',
                'max:200',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'brand' => [
                'required',
                'string',
                'max:100',
            ],

            'model' => [
                'required',
                'string',
                'max:100',
            ],

            'serial_number' => [
                'required',
                'string',
                'max:100',
            ],

            'location_id' => [
                'required',
                'integer',
                'exists:locations,id',
            ],

            'assigned_user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'purchase_date' => [
                'required',
                'date',
            ],

            'purchase_proof_number' => [
                'required',
                'string',
                'max:100',
            ],

            'acquisition_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'condition_status' => [
                'required',
                Rule::in([
                    'good',
                    'fair',
                    'poor',
                    'damaged',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'asset_request_id' => [
                'nullable',
                'integer',
                'exists:asset_requests,id',
            ],

            'request_item_id' => [
                'nullable',
                'integer',
                'exists:asset_request_items,id',
            ],

            /*
             * Bukti pembelian.
             */
            'purchase_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],

            /*
             * Foto asset.
             */
            'photo_asset' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            /*
             * Foto letak stiker asset.
             */
            'photo_sticker' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            /*
             * Foto lokasi asset.
             */
            'photo_location' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            /*
             * Foto memo.
             */
            'photo_memo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }
}