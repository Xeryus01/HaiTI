<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationViewController extends Controller
{
    /**
     * Display notifications page
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Display single notification
     */
    public function show(Notification $notification)
    {
        // Verify the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();

        return view('notifications.show', compact('notification'));
    }
}
