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
            'category' => 'sometimes|string|in:DATA_PROCESSING,EMAIL_SSO,HARDWARE_SUPPORT,SOFTWARE_SUPPORT,NETWORK_SUPPORT,SECURITY_INCIDENT',
            'title' => 'sometimes|string|max:200',
            'description' => 'sometimes|string|max:5000',
            'status' => ['nullable', 'string', Rule::in($allowedStatus)],
            'asset_id' => 'nullable|integer|exists:assets,id',
            'assignee_id' => 'nullable|integer|exists:users,id',
            'attachment' => 'nullable|file|max:10240',
        ];
    }
}
