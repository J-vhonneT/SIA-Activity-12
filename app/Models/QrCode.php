<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'reservation_id',
        'qr_code_data',
        'status',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}