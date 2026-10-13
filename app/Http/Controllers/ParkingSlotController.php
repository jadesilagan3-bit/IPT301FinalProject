<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Http\Request;

class ParkingSlotController extends Controller
{
    public function index()
    {
        $parkingSlots = ParkingSlot::all();
        return view('parking-slots.index', compact('parkingSlots'));
    }

    public function create()
    {
        return view('parking-slots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slot_number' => 'required|string|max:255|unique:parking_slots,slot_number',
            'slot_type' => 'required|string|max:255',
            'status' => 'required|in:Available,Occupied,Maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        ParkingSlot::create($data);

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot added successfully.');
    }

    public function edit(ParkingSlot $parking_slot)
    {
        return view('parking-slots.edit', [
            'parkingSlot' => $parking_slot
        ]);
    }

    public function update(Request $request, ParkingSlot $parking_slot)
    {
        $data = $request->validate([
            'slot_number' => 'required|string|max:255|unique:parking_slots,slot_number,' . $parking_slot->id,
            'slot_type' => 'required|string|max:255',
            'status' => 'required|in:Available,Occupied,Maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        $parking_slot->update($data);

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot updated successfully.');
    }

    public function destroy(ParkingSlot $parking_slot)
    {
        if ($parking_slot->parkingSessions()->exists()) {
            return back()->with('error', 'Cannot delete a slot with parking session records.');
        }

        $parking_slot->delete();

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot deleted successfully.');
    }
}
