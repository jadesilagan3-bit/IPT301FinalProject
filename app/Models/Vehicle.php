<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'vehicle_type', 'brand', 'model', 'color', 'owner_id'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function parkingSessions()   
    {
        return $this->hasMany(ParkingSession::class);
    }
}