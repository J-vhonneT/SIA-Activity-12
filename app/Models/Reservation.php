<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'slot_number',
        'reservation_date',
        'start_time',
        'end_time',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'reservation_date' => 'date',
    ];

    public function qrCode()
    {
        return $this->hasOne(QRCode::class);
    }

    /**
     * Get the user that owns the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction log for the reservation.
     */
    public function transactions()
    {
        return $this->hasMany(ReservationTransaction::class);
    }
}