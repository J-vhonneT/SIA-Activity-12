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
        // Data for the new time-range based UI
        $todaysReservations = Reservation::whereDate('reservation_date', Carbon::today())
                                     ->where('expires_at', '>', now()->toDateTimeString())
                                     ->orderBy('start_time')
                                     ->get();

        return view('reservations.create', compact('slots', 'todaysReservations'));
    }

    public function getBookedSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Explicitly format times to strings for robust database comparison
        $startTime = Carbon::createFromFormat('H:i', $request->start_time)->format('H:i:s');
        $endTime = Carbon::createFromFormat('H:i', $request->end_time)->format('H:i:s');

        // Find all slots that have a reservation that overlaps with the requested time range.
        $bookedSlots = Reservation::where('reservation_date', $request->date)
            ->where('expires_at', '>', now()->toDateTimeString())
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->pluck('slot_number')
            ->unique()
            ->toArray();

        return response()->json(['booked_slots' => $bookedSlots]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'slot_number' => ['required', 'integer', 'min:1', 'max:12'],
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = Carbon::createFromFormat('H:i', $request->end_time);

        // Rule 1: Enforce the 5-hour maximum booking duration.
        if ($startTime->diffInHours($endTime) > 5) {
            return back()->with('error', 'You can book a slot for a maximum of 5 hours.');
        }

        // Explicitly format times for the query
        $startTimeStr = $startTime->format('H:i:s');
        $endTimeStr = $endTime->format('H:i:s');

        // Rule 2: Check for conflicting reservations.
        $isConflict = Reservation::where('slot_number', $request->slot_number)
            ->where('reservation_date', $request->reservation_date)
            ->where('expires_at', '>', now()->toDateTimeString())
            ->where(function ($query) use ($startTimeStr, $endTimeStr) {
                $query->where('start_time', '<', $endTimeStr)
                      ->where('end_time', '>', $startTimeStr);
            })
            ->exists();

        if ($isConflict) {
            return back()->with('error', 'This slot is unavailable for the selected time range. It conflicts with an existing booking.');
        }

        // All checks passed, create the reservation.
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'slot_number' => $request->slot_number,
            'reservation_date' => $request->reservation_date,
            'start_time' => $startTimeStr,
            'end_time' => $endTimeStr,
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

        return redirect()->route('dashboard')
                         ->with('success', 'Reservation confirmed successfully!')
                         ->with('new_qr_code', $qrCodeData)
                         ->with('new_reservation', $reservation);
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