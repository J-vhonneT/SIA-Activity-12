<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'slot_number',
        'reservation_date',
        'reservation_time',
        'expires_at',
    ];

    public function qrCode()
    {
        return $this->hasOne(QRCode::class);
    }

    /**
     * Get the transaction log for the reservation.
     */
    public function transactions()
    {
        return $this->hasMany(ReservationTransaction::class);
    }
}