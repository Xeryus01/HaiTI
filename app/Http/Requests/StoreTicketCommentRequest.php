<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'message' => 'required|string|max:5000',
            'is_internal' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Pesan komentar harus diisi',
            'message.max' => 'Pesan komentar maksimal 5000 karakter',
        ];
    }
}
