<?php


namespace App\Http\Controllers;


use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ReservationController extends Controller
{
 
    public function index(Request $request)
    {
        $query = Reservation::where('user_id', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search by date string
                $q->where('reservation_date', 'like', "%$search%");

                // Intelligently search by slot number
                if (preg_match('/^slot\s*(\d+)/i', $search, $matches)) {
                    // Handles "Slot 5", "slot5", etc.
                    $q->orWhere('slot_number', $matches[1]);
                } elseif (is_numeric($search)) {
                    // Handles "5"
                    $q->orWhere('slot_number', $search);
                }
            });
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(5);
        
        // Always show all slots in the form. Validation will be handled on submission.
        $slots = range(1, 12);

        return view('reservations.index', compact('reservations', 'slots'));
    }


 
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
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
            'reservation_time' => $request->reservation_time, // Changed from duration_hours
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


    public function edit(Reservation $reservation)
    {
        return view('reservations.edit', compact('reservation'));
    }


    public function update(Request $request, Reservation $reservation)
    {
        $request->validate([
            'slot_number' => 'required|integer|min:1|max:12',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);


        $reservation->update($request->all());


        return redirect()->route('reservations.index')->with('success', 'Reservation updated successfully!');
    }


    public function destroy(Reservation $reservation)
    {
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