<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(['admin', 'technician']) || 
               $this->ticket->requester_id === $this->user()->id;
    }

    public function rules(): array
    {
        $statusList = implode(',', 
            array_map(fn($s) => $s, \App\Models\Ticket::statuses())
        );

        return [
            'category' => 'sometimes|string|in:MAINTENANCE,ZOOM_SUPPORT,IT_SUPPORT,OTHER',
            'title' => 'sometimes|string|max:200',
            'description' => 'sometimes|string|max:5000',
            'priority' => 'sometimes|string|in:LOW,MEDIUM,HIGH,CRITICAL',
            'status' => "sometimes|string|in:$statusList",
            'asset_id' => 'nullable|integer|exists:assets,id',
            'assignee_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
