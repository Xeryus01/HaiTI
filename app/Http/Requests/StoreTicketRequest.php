<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'required|in:MAINTENANCE,ZOOM_SUPPORT,IT_SUPPORT,OTHER',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'asset_id' => 'nullable|exists:assets,id',
        ];
    }
}
