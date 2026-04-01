<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/exports/tickets', [ExportController::class, 'tickets'])->name('exports.tickets');
    Route::get('/exports/reservations', [ExportController::class, 'reservations'])->name('exports.reservations');

    // simple blade views for our features
    Route::resource('tickets', \App\Http\Controllers\TicketViewController::class);
    Route::post('tickets/{ticket}/comments', [\App\Http\Controllers\TicketViewController::class, 'comment'])->name('tickets.comment');
    Route::post('tickets/{ticket}/attachments', [\App\Http\Controllers\TicketViewController::class, 'uploadAttachment'])->name('tickets.attachments.store');
    Route::get('tickets/{ticket}/attachments/{attachment}', [\App\Http\Controllers\TicketViewController::class, 'showAttachment'])->name('tickets.attachments.show');

    Route::resource('assets', \App\Http\Controllers\AssetViewController::class);
    Route::resource('reservations', \App\Http\Controllers\ReservationViewController::class);
    Route::get('reservations/{reservation}/nota-dinas', [\App\Http\Controllers\ReservationViewController::class, 'showNotaDinas'])->name('reservations.nota-dinas');

    // notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationViewController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [\App\Http\Controllers\NotificationViewController::class, 'show'])->name('notifications.show');
});

require __DIR__.'/auth.php';
