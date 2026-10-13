<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use App\Models\ParkingSession;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOwners = Owner::count();
        $totalVehicles = Vehicle::count();
        $totalParkingSlots = ParkingSlot::count();
        $totalParkingSessions = ParkingSession::count();
        $totalPayments = Payment::count();

        $parkingRecords = ParkingSession::with([
            'vehicle.owner',
            'parkingSlot',
            'payment'
        ])->latest()->get();

        return view('dashboard', compact(
            'totalOwners',
            'totalVehicles',
            'totalParkingSlots',
            'totalParkingSessions',
            'totalPayments',
            'parkingRecords'
        ));
    }
}
