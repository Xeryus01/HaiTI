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
            'category' => 'required|in:DATA_PROCESSING,EMAIL_SSO,HARDWARE_SUPPORT,SOFTWARE_SUPPORT,NETWORK_SUPPORT,SECURITY_INCIDENT',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'priority' => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'asset_id' => 'nullable|exists:assets,id',
            'attachment' => 'nullable|file|max:10240',
        ];
    }
}
