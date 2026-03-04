<?php

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

    // simple blade views for our features
    Route::resource('tickets', \App\Http\Controllers\TicketViewController::class);
    Route::post('tickets/{ticket}/comments', [\App\Http\Controllers\TicketViewController::class, 'comment'])->name('tickets.comment');

    Route::resource('assets', \App\Http\Controllers\AssetViewController::class);
    Route::resource('reservations', \App\Http\Controllers\ReservationViewController::class);

    // notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationViewController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [\App\Http\Controllers\NotificationViewController::class, 'show'])->name('notifications.show');
});

require __DIR__.'/auth.php';
