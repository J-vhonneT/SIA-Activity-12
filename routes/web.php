<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/reservations/pdf', [App\Http\Controllers\ReservationController::class, 'exportPDF'])->name('reservations.pdf');
Route::resource('reservations', ReservationController::class)->middleware(['auth', 'verified']);

use App\Models\Reservation;

Route::get('/qr-code', function () {
    $reservationId = request()->query('reservation_id');
    if (!$reservationId) {
        abort(404, 'Reservation not found.');
    }
    $reservation = App\Models\Reservation::findOrFail($reservationId);
    return view('qr-code.index', compact('reservation'));
})->middleware(['auth', 'verified'])->name('qr-code.index');

Route::get('/reservations/pdf', [App\Http\Controllers\ReservationController::class, 'exportPDF'])->name('reservations.pdf');


Route::get('/qr-code/latest', [App\Http\Controllers\ReservationController::class, 'showLatestQr'])
    ->middleware(['auth', 'verified'])
    ->name('qr-code.latest');


Route::get('/cabinet-access-guide', function () {
    return view('cabinet-access-guide.index');
})->middleware(['auth', 'verified'])->name('cabinet-access-guide.index');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';