<?php

namespace App\Http\Controllers;

use App\Models\ParkingSession;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ParkingSessionController extends Controller
{
    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function create()
    {
        return redirect()->route('register-parking.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('register-parking.create');
    }

    public function show($id)
    {
        return redirect()->route('parking-sessions.edit', $id);
    }

    public function edit($id)
    {
        $parkingSession = ParkingSession::with([
            'vehicle.owner',
            'parkingSlot',
            'payment'
        ])->findOrFail($id);

        $vehicles = Vehicle::with('owner')
            ->orderBy('plate_number')
            ->get();

        $parkingSlots = ParkingSlot::where(function ($query) use ($parkingSession) {
            $query->where('status', 'Available')
                ->orWhere('id', $parkingSession->parking_slot_id);
        })->orderBy('slot_number')->get();

        return view('parking-sessions.edit', compact(
            'parkingSession',
            'vehicles',
            'parkingSlots'
        ));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'parking_slot_id' => 'required|exists:parking_slots,id',
            'entry_time' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $session = ParkingSession::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldSlot = ParkingSlot::whereKey($session->parking_slot_id)
                ->lockForUpdate()
                ->firstOrFail();

            $newSlot = ParkingSlot::whereKey($validated['parking_slot_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($session->status === 'Active') {
                if (
                    $newSlot->id !== $oldSlot->id &&
                    $newSlot->status !== 'Available'
                ) {
                    throw ValidationException::withMessages([
                        'parking_slot_id' => 'That parking slot is not available.',
                    ]);
                }

                if ($newSlot->id !== $oldSlot->id) {
                    $oldSlot->update(['status' => 'Available']);
                    $newSlot->update(['status' => 'Occupied']);
                }
            }

            $session->vehicle_id = $validated['vehicle_id'];
            $session->parking_slot_id = $newSlot->id;
            $session->entry_time = $validated['entry_time'];

            if ($session->exit_time) {
                $session->duration_minutes = abs(
                    \Carbon\Carbon::parse($validated['entry_time'])
                        ->diffInMinutes($session->exit_time)
                );
            }

            $session->save();
        });

        return redirect()->route('dashboard')
            ->with('success', 'Parking record updated successfully.');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $session = ParkingSession::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($session->payment()->exists()) {
                throw ValidationException::withMessages([
                    'session' => 'This session has a payment record and cannot be deleted.',
                ]);
            }

            if ($session->status === 'Active') {
                ParkingSlot::whereKey($session->parking_slot_id)
                    ->update(['status' => 'Available']);
            }

            $session->delete();
        });

        return redirect()->route('dashboard')
            ->with('success', 'Parking record deleted successfully.');
    }
}
