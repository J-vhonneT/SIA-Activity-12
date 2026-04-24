<?php


namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\QRCode;
use App\Models\ReservationTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;


use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
 
    public function index(Request $request)
    {
        $this->authorize('viewAny', Reservation::class); // Ensure only authorized users can see the list

        $query = Reservation::query();

        if ($request->filled('search')) {
            $search = $request->search;
            if (is_numeric($search)) {
                $query->where('slot_number', $search);
            } elseif (preg_match('/^slot\s*(\d+)/i', $search, $matches)) {
                $query->where('slot_number', $matches[1]);
            } else {
                $query->where('reservation_date', 'like', "%$search%");
            }
        }

        $reservations = $query->with('user')->orderBy('created_at', 'desc')->paginate(10);
        
        return view('reservations.index', compact('reservations'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        // $this->authorize('update', $reservation);
        $slots = range(1, 12);
        return view('reservations.edit', compact('reservation', 'slots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        // $this->authorize('update', $reservation);

        $validated = $request->validate([
            'slot_number' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('reservations')->where(function ($query) use ($request, $reservation) {
                    return $query->where('reservation_date', $request->reservation_date)
                                 ->where('id', '!=', $reservation->id);
                }),
            ],
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully!');
    }
 
    public function create()
    {
        $slots = range(1, 12);
        $bookedSlots = Reservation::where('reservation_date', Carbon::today())->pluck('slot_number')->toArray();
        return view('reservations.index', compact('slots', 'bookedSlots'));
    }


    public function store(Request $request)
    {
        // First, check if the day is already full.
        $reservationsToday = Reservation::where('reservation_date', $request->reservation_date)->count();
        if ($reservationsToday >= 12) {
            return back()->with('error', 'Sorry, all slots for this day are already booked.');
        }

        $request->validate([
            'slot_number' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                Rule::unique('reservations')->where(function ($query) use ($request) {
                    return $query->where('reservation_date', $request->reservation_date)
                                 ->where('reservation_time', $request->reservation_time);
                }),
            ],
            'reservation_date' => 'required|date',
            'reservation_time' => 'required', // Changed from duration_hours
        ], [
            'slot_number.unique' => 'This slot is already booked for the selected date and time. Please choose another time or slot.'
        ]);

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'slot_number' => $request->slot_number,
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        // Log the creation event
        $reservation->transactions()->create([
            'user_id' => $reservation->user_id,
            'event_type' => 'created',
            'details' => 'Reservation created by user.',
            'event_timestamp' => now(),
        ]);

        // Generate a unique token for the QR code
        $token = Str::random(40);

        // Generate and save the QR code
        $qrCodeData = QrCodeGenerator::format('svg')->size(200)->generate($token);
        $reservation->qrCode()->create([
            'qr_code_data' => $qrCodeData,
            'status' => 'active',
            'token' => $token, // Save the token
        ]);

        // Log the QR code generation event
        $reservation->transactions()->create([
            'user_id' => $reservation->user_id,
            'event_type' => 'qr_generated',
            'details' => 'QR code generated for the reservation.',
            'event_timestamp' => now(),
        ]);

        return redirect()->route('qr-code.index', ['reservation_id' => $reservation->id])
                         ->with('success', 'Reservation confirmed successfully!');
    }

    public function showLatestQr()
    {
        $latestReservation = Reservation::where('user_id', auth()->id())
                                        ->latest('created_at')
                                        ->first();

        if ($latestReservation) {
            return redirect()->route('qr-code.index', ['reservation_id' => $latestReservation->id]);
        }

        return redirect()->route('reservations.index')->with('error', 'You have no reservations to show a QR code for.');
    }


    public function show(Reservation $reservation)
    {
        //
    }


    public function destroy(Reservation $reservation)
    {
        // $this->authorize('delete', $reservation);
        $reservation->delete();
        return redirect()->route('reservations.index')->with('success', 'Reservation cancelled successfully!');
    }

    public function exportPDF()
    {
        $reservations = Reservation::where('user_id', auth()->id())->get();
        $pdf = app('dompdf.wrapper')->loadView('reservations.pdf', compact('reservations'));
        return $pdf->download('reservations.pdf');
    }
}