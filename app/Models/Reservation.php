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
    ];
}