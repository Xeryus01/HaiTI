<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function summary()
    {
        // Single query for asset counts
        $assetCounts = \DB::table('assets')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'ACTIVE' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'INACTIVE' THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN condition = 'DAMAGED' THEN 1 ELSE 0 END) as damaged
            ")
            ->first();

        // Single query for ticket counts
        $ticketCounts = \DB::table('tickets')
            ->selectRaw("
                SUM(CASE WHEN status = 'OPEN' THEN 1 ELSE 0 END) as open,
                SUM(CASE WHEN status = 'ASSIGNED_DETECT' THEN 1 ELSE 0 END) as assigned_detect,
                SUM(CASE WHEN status = 'SOLVED_WITH_NOTES' THEN 1 ELSE 0 END) as solved_with_notes,
                SUM(CASE WHEN status = 'SOLVED' THEN 1 ELSE 0 END) as solved,
                SUM(CASE WHEN status = 'REJECTED' THEN 1 ELSE 0 END) as rejected
            ")
            ->first();

        return response()->json([
            'assets' => [
                'total' => $assetCounts->total,
                'active' => $assetCounts->active,
                'inactive' => $assetCounts->inactive,
                'damaged' => $assetCounts->damaged,
            ],
            'tickets' => [
                'open' => $ticketCounts->open,
                'assigned_detect' => $ticketCounts->assigned_detect,
                'solved_with_notes' => $ticketCounts->solved_with_notes,
                'solved' => $ticketCounts->solved,
                'rejected' => $ticketCounts->rejected,
            ],
            'latest_tickets' => Ticket::latest()->take(10)->get([
                'id', 'code', 'title', 'status', 'priority', 'created_at',
            ]),
        ]);
    }
}
