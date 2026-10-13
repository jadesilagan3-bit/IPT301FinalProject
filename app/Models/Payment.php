<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_session_id', 'amount', 'payment_date',
        'payment_method', 'payment_status'
    ];

    public function parkingSession()
    {
        return $this->belongsTo(ParkingSession::class);
    }
}