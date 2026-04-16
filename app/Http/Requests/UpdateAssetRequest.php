<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['Admin', 'Teknisi']) ?? false;
    }

    public function rules(): array
    {
        return [
            'asset_code' => 'sometimes|string|max:50|unique:assets,asset_code,' . $this->asset->id,
            'name' => 'sometimes|string|max:150',
            'type' => 'sometimes|string|max:50',
            'brand' => 'nullable|string|max:80',
            'model' => 'nullable|string|max:80',
            'serial_number' => 'nullable|string|max:120|unique:assets,serial_number,' . $this->asset->id,
            'specs' => 'nullable|json',
            'location' => 'nullable|string|max:120',
            'holder' => 'nullable|string|max:120',
            'status' => 'sometimes|string|in:ACTIVE,INACTIVE',
            'condition' => 'sometimes|string|in:GOOD,FAIR,POOR,DAMAGED',
            'purchased_at' => 'nullable|date',
        ];
    }
}
