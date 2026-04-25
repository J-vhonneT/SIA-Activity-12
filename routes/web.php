<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\QRCodeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Routes for all authenticated users
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/booking-success/{reservation}', [ReservationController::class, 'showBookingSuccess'])->name('reservations.booking-success');

    Route::get('/cabinet-access-guide', function () {
        return view('cabinet-access-guide.index');
    })->name('cabinet-access-guide.index');

    Route::get('/qr-code', [QRCodeController::class, 'index'])->name('qr-code.index');
    Route::get('/qr-code/latest', [QRCodeController::class, 'latest'])->name('qr-code.latest');

    // Routes accessible only by admin and staff
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

        Route::get('/reservations/pdf', [App\Http\Controllers\ReservationController::class, 'exportPDF'])->name('reservations.pdf');
    });
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';