<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'slot_number', 'slot_type', 'status', 'location'
    ];

    public function parkingSessions()
    {
        return $this->hasMany(ParkingSession::class);
    }
}