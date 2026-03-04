<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $user = $request->user();

        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB
            'comment_id' => 'nullable|exists:ticket_comments,id',
        ]);

        $file = $validated['file'];
        $path = $file->store('attachments');

        $attachment = Attachment::create([
            'ticket_id' => $ticket->id,
            'comment_id' => $validated['comment_id'] ?? null,
            'uploader_id' => $user->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size_bytes' => $file->getSize(),
        ]);

        return response()->json($attachment, 201);
    }
}
