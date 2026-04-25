<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Reservation;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $stats = [];
        $cabinets = [];

        if ($user->role === 'admin' || $user->role === 'staff') {
            $totalCabinets = 12; // Corrected to 12 total cabinets
            $reservationsToday = Reservation::with('user')
                                ->whereDate('reservation_date', Carbon::today())
                                ->get();

            $activeReservations = $reservationsToday->count();
            $availableCabinets = $totalCabinets - $activeReservations;

            $stats = [
                'totalUsers' => User::count(),
                'activeReservations' => $activeReservations,
                'availableCabinets' => $availableCabinets,
            ];

            // Prepare cabinet status data
            $reservedSlots = $reservationsToday->keyBy('slot_number');
            for ($i = 1; $i <= $totalCabinets; $i++) {
                if ($reservedSlots->has($i)) {
                    $reservation = $reservedSlots->get($i);
                    $cabinets[$i] = [
                        'status' => 'reserved',
                        'user_name' => $reservation->user->name,
                        'time' => Carbon::parse($reservation->reservation_time)->format('g:i A'),
                    ];
                } else {
                    $cabinets[$i] = [
                        'status' => 'available',
                        'user_name' => null,
                        'time' => null,
                    ];
                }
            }
        }

        return view('dashboard', compact('user', 'stats', 'cabinets'));
    }
}