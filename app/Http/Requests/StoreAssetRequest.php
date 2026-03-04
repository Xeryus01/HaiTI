<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'technician']);
    }

    public function rules(): array
    {
        return [
            'asset_code' => 'required|string|max:50|unique:assets',
            'name' => 'required|string|max:150',
            'type' => 'required|string|max:50',
            'brand' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:80',
            'serial_number' => 'nullable|string|max:120|unique:assets',
            'specs' => 'nullable|json',
            'location' => 'nullable|string|max:120',
            'status' => 'required|string|in:ACTIVE,INACTIVE,MAINTENANCE,SOLD',
            'purchased_at' => 'nullable|date',
        ];
    }
}
