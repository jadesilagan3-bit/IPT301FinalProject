<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id', 'parking_slot_id', 'entry_time',
        'exit_time', 'duration_minutes', 'status'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function parkingSlot()
    {
        return $this->belongsTo(ParkingSlot::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}