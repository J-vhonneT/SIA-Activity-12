<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\DashboardController;
use App\Models\Reservation;
/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard (ONLY ONE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Reservations (User + Admin access depending on controller logic)
    Route::get('/reservations/create', [ReservationController::class, 'create'])
        ->name('reservations.create');

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::get('/booked-slots', [ReservationController::class, 'getBookedSlots'])
        ->name('reservations.booked-slots');

    Route::get('/booking-success/{reservation}', [ReservationController::class, 'showBookingSuccess'])
        ->name('reservations.booking-success');

    Route::get('/cabinet-access-guide', function () {
        return view('cabinet-access-guide.index');
    })->name('cabinet-access-guide.index');

    Route::get('/qr-code', [QRCodeController::class, 'index'])
        ->name('qr-code.index');

    Route::get('/qr-code/latest', [QRCodeController::class, 'latest'])
        ->name('qr-code.latest');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    });

    // Manage Reservations (Admin only)
    Route::get('/manage-reservations', [ReservationController::class, 'manageReservations'])
        ->name('manage-reservations');

    Route::get('/manage-reservations/export-pdf', [ReservationController::class, 'exportAdminPDF'])
        ->name('manage-reservations.export-pdf');

    // User Management (CRUD)
    //Route::resource('/users', UserController::class);

    // Reservation management (Admin full control)
    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])
        ->name('reservations.edit');

    Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])
        ->name('reservations.update');

    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');

    Route::get('/reservations/pdf', [ReservationController::class, 'exportPDF'])
        ->name('reservations.pdf');
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES (USER SETTINGS)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Laravel Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';