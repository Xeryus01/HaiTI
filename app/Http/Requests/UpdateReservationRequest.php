<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $reservation = $this->route('reservation');

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Teknisi'])
            || ($reservation && $reservation->requester_id === $user->id);
    }

    public function rules(): array
    {
        return [
            'room_name' => 'sometimes|string|max:100',
            'purpose' => 'sometimes|string|max:500',
            'start_time' => 'sometimes|date_format:Y-m-d H:i|after:now',
            'end_time' => 'sometimes|date_format:Y-m-d H:i|after:start_time',
            'status' => 'sometimes|string|in:PENDING,APPROVED,REJECTED,COMPLETED,CANCELLED',
            'zoom_link' => 'nullable|url|max:255',
            'notes' => 'nullable|string|max:2000',
        ];
    }
}
