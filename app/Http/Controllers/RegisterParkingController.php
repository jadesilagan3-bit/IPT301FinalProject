<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use App\Models\ParkingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterParkingController extends Controller
{
    public function create()
    {
        $owners = Owner::orderBy('first_name')->get();

        $parkingSlots = ParkingSlot::where('status', 'Available')
            ->orderBy('slot_number')
            ->get();

        return view('register-parking', compact(
            'owners',
            'parkingSlots'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_option' => 'required|in:existing,new',
            'owner_id' => 'required_if:owner_option,existing|nullable|exists:owners,id',

            'first_name' => 'required_if:owner_option,new|nullable|string|max:255',
            'last_name' => 'required_if:owner_option,new|nullable|string|max:255',
            'contact_no' => 'required_if:owner_option,new|nullable|string|max:30',
            'email' => 'nullable|email|max:255|unique:owners,email',
            'address' => 'nullable|string|max:255',

            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',

            'parking_slot_id' => 'required|exists:parking_slots,id',
        ]);

        DB::transaction(function () use ($validated) {
            $slot = ParkingSlot::whereKey($validated['parking_slot_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($slot->status !== 'Available') {
                throw ValidationException::withMessages([
                    'parking_slot_id' => 'This slot is no longer available. Please select another slot.',
                ]);
            }

            if ($validated['owner_option'] === 'existing') {
                $owner = Owner::findOrFail($validated['owner_id']);
            } else {
                $owner = Owner::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'contact_no' => $validated['contact_no'],
                    'email' => $validated['email'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            }

            $vehicle = Vehicle::create([
                'plate_number' => $validated['plate_number'],
                'vehicle_type' => $validated['vehicle_type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'color' => $validated['color'],
                'owner_id' => $owner->id,
            ]);

            ParkingSession::create([
                'vehicle_id' => $vehicle->id,
                'parking_slot_id' => $slot->id,
                'entry_time' => now(),
                'status' => 'Active',
            ]);

            $slot->update([
                'status' => 'Occupied',
            ]);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Parking registered successfully. The vehicle, owner, and parking slot are connected.');
    }
}
