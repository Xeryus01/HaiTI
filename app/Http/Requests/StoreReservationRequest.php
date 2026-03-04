<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_name' => 'required|string|max:100',
            'purpose' => 'required|string|max:500',
            'start_time' => 'required|date_format:Y-m-d H:i|after:now',
            'end_time' => 'required|date_format:Y-m-d H:i|after:start_time',
        ];
    }

    public function messages(): array
    {
        return [
            'room_name.required' => 'Nama ruang harus diisi',
            'purpose.required' => 'Tujuan reservasi harus diisi',
            'start_time.required' => 'Waktu mulai harus diisi',
            'start_time.after' => 'Waktu mulai harus di masa depan',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai',
        ];
    }
}
