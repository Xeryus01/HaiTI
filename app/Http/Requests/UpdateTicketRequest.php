<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $ticket = $this->route('ticket');

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole(['Admin', 'Teknisi'])
            || ($ticket && $ticket->requester_id === $user->id);
    }

    public function rules(): array
    {
        $allowedStatus = array_merge([''], \App\Models\Ticket::statuses());

        return [
            'category' => 'sometimes|string|in:MAINTENANCE,ZOOM_SUPPORT,IT_SUPPORT,OTHER',
            'title' => 'sometimes|string|max:200',
            'description' => 'sometimes|string|max:5000',
            'priority' => 'sometimes|string|in:LOW,MEDIUM,HIGH,CRITICAL',
            'status' => ['nullable', 'string', Rule::in($allowedStatus)],
            'asset_id' => 'nullable|integer|exists:assets,id',
            'assignee_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
