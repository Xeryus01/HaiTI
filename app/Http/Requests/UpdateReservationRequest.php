<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin') || 
               $this->reservation->requester_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'room_name' => 'sometimes|string|max:100',
            'purpose' => 'sometimes|string|max:500',
            'start_time' => 'sometimes|date_format:Y-m-d H:i|after:now',
            'end_time' => 'sometimes|date_format:Y-m-d H:i',
            'status' => 'sometimes|string|in:PENDING,APPROVED,REJECTED,COMPLETED,CANCELLED',
        ];
    }
}
