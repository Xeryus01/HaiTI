<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeAssetHolderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('manage assets');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_holder' => 'required|string|max:255',
            'changed_at' => 'required|date_format:Y-m-d\TH:i',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'new_holder.required' => 'Pemegang aset baru harus diisi',
            'new_holder.string' => 'Pemegang aset harus berupa teks',
            'changed_at.required' => 'Tanggal perubahan harus diisi',
            'changed_at.date_format' => 'Tanggal perubahan harus format YYYY-MM-DDTHH:MM',
            'notes.string' => 'Catatan harus berupa teks',
        ];
    }
}
