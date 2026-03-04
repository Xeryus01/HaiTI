<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketCommentController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\NotificationController;
use App\Models\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This application uses Laravel Breeze for web auth. For API auth we
| recommend Sanctum (cookie-based or token-based).
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);

    // tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}', [TicketController::class, 'updateStatus']);
    Route::post('/tickets/{ticket}/status', [TicketController::class, 'updateStatus']);

    // file attachments for tickets / comments
    Route::post('/tickets/{ticket}/attachments', [\App\Http\Controllers\Api\AttachmentController::class, 'store']);
    Route::post('/tickets/{ticket}/comments', [TicketCommentController::class, 'store']);

    // assets
    Route::apiResource('assets', AssetController::class);

    // reservations
    Route::apiResource('reservations', ReservationController::class);

    // notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/latest-unread', [NotificationController::class, 'latestUnread']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::get('/notifications/{notification}', [NotificationController::class, 'show']);
    Route::patch('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::patch('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

    // logs (read-only)
    Route::get('/logs', function (Request $request) {
        $q = App\Models\Log::query();
        if (! $request->user()->hasRole('Admin')) {
            $q->where('actor_id', $request->user()->id);
        }
        return $q->latest()->paginate(20);
    });
});

