<?php

namespace App\Http\Requests\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssetRequest extends FormRequest
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
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
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
    Rule::exists('users', 'id')
        ->where(function ($query) {
            $query->where('location_id', $this->location_id);
        }),
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
                    'lost',
                ]),
            ],

            'asset_status' => [
                'required',
                Rule::in([
                    'active',
                    'maintenance',
                    'disposed',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],


            'purchase_proof' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:5120',
],

'photo_asset' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:5120',
],

'photo_sticker' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:5120',
],

'photo_location' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:5120',
],

'photo_memo' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:5120',
],

        ];
    }
}