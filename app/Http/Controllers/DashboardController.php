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

        if ($user->role === 'admin' || $user->role === 'staff') {
            $totalCabinets = 50; // Assuming 50 total cabinets
            $activeReservations = Reservation::whereDate('reservation_date', Carbon::today())->count();
            $availableCabinets = $totalCabinets - $activeReservations;

            $stats = [
                'totalUsers' => User::count(),
                'activeReservations' => $activeReservations,
                'availableCabinets' => $availableCabinets,
            ];
        }

        return view('dashboard', compact('user', 'stats'));
    }
}