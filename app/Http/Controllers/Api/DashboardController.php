<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function summary()
    {
        return response()->json([
            'assets' => [
                'total' => Asset::count(),
                'active' => Asset::where('status', 'ACTIVE')->count(),
                'broken' => Asset::where('status', 'BROKEN')->count(),
                'repair' => Asset::whereIn('status', ['MAINTENANCE', 'REPAIR'])->count(),
            ],
            'tickets' => [
                'open' => Ticket::where('status', Ticket::STATUS_OPEN)->count(),
                'assigned_detect' => Ticket::where('status', Ticket::STATUS_ASSIGNED_DETECT)->count(),
                'solved_with_notes' => Ticket::where('status', Ticket::STATUS_SOLVED_WITH_NOTES)->count(),
                'solved' => Ticket::where('status', Ticket::STATUS_SOLVED)->count(),
                // keep rejected for reporting if needed
                'rejected' => Ticket::where('status', Ticket::STATUS_REJECTED)->count(),
            ],
            'latest_tickets' => Ticket::latest()->take(10)->get([
                'id', 'code', 'title', 'status', 'priority', 'created_at',
            ]),
        ]);
    }
}
