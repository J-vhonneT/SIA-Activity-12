<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\QrCode;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole(['admin', 'staff'])) {
            // Admin/Staff: Show all reservations
            $query = Reservation::with('user', 'qrCode')->latest('created_at');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('slot_number', 'like', "%{$search}%");
            }

            $reservations = $query->paginate(10);

            return view('qr-code.index', compact('reservations'));
        } else {
            // Regular User: Show their own reservations
            $reservationId = $request->reservation_id;

            if (!$reservationId) {
                $latestReservationForId = Reservation::where('user_id', $user->id)->latest('created_at')->first();
                if ($latestReservationForId) {
                    $reservationId = $latestReservationForId->id;
                } else {
                    return redirect()->route('dashboard')->with('error', 'You have no reservations.');
                }
            }

            $latestReservation = Reservation::with('qrCode')->findOrFail($reservationId);

            if ($latestReservation->user_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            }

            return view('qr-code.index', compact('latestReservation'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(QrCode $qrCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QrCode $qrCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QrCode $qrCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QrCode $qrCode)
    {
        //
    }

    /**
     * Find the latest reservation for the user and redirect to its QR code.
     */
    public function latest()
    {
        $latestReservation = Reservation::where('user_id', auth()->id())->latest('created_at')->first();

        if (!$latestReservation) {
            return redirect()->route('reservations.index')->with('error', 'You have no reservations.');
        }

        return redirect()->route('qr-code.index', ['reservation_id' => $latestReservation->id]);
    }
}