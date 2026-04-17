<!DOCTYPE html>
<html>
<head>
    <title>Reservations PDF</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #dddddd; text-align: left; padding: 8px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Your Reservations</h1>

    @foreach($reservations as $res)
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px; border: 2px solid #ccc;">
            <thead>
                <tr>
                    <th colspan="2" style="background-color: #f2f2f2; padding: 8px; text-align: center; font-size: 1.2em;">
                        Reservation ID: {{ $res->id }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 1px solid #dddddd; padding: 8px; width: 120px;"><strong>Slot</strong></td>
                    <td style="border: 1px solid #dddddd; padding: 8px;">Slot {{ $res->slot_number }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #dddddd; padding: 8px;"><strong>Date</strong></td>
                    <td style="border: 1px solid #dddddd; padding: 8px;">{{ $res->reservation_date }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #dddddd; padding: 8px;"><strong>Time</strong></td>
                    <td style="border: 1px solid #dddddd; padding: 8px;">{{ \Carbon\Carbon::parse($res->reservation_time)->format('g:i A') }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #dddddd; padding: 8px;"><strong>Booked On</strong></td>
                    <td style="border: 1px solid #dddddd; padding: 8px;">{{ $res->created_at->format('Y-m-d g:i A') }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #dddddd; padding: 8px;"><strong>QR Code</strong></td>
                    <td style="border: 1px solid #dddddd; padding: 8px; text-align: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode('RESERVATION_ID:' . $res->id) }}" alt="QR Code">
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html>