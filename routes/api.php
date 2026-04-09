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
    Route::apiResource('assets', AssetController::class)->names('api.assets');

    // reservations
    Route::apiResource('reservations', ReservationController::class)->names('api.reservations');

    // notifications - web auth for header dropdown
    // these routes are in web session flow (not Sanctum token), so they are also accessible from frontend layout navbar
    // (SPA uses same-origin cookies, auth middleware)
    
    // handled in routes/web.php now to keep standard web cookie auth and avoid sanctum guard mismatch.

    // logs (read-only)
    Route::get('/logs', function (Request $request) {
        $q = App\Models\Log::query();
        if (! $request->user()->hasRole('Admin')) {
            $q->where('actor_id', $request->user()->id);
        }
        return $q->latest()->paginate(20);
    });
});

