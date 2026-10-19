<?php

namespace App\Http\Controllers;

use App\Models\ParkingSlot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParkingSlotController extends Controller
{
    public function index()
    {
        $slots = ParkingSlot::latest()->get();

        return view('parking_slots.index', compact('slots'));
    }

    public function create()
    {
        return view('parking_slots.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_number' => 'required|string|max:50|unique:parking_slots,slot_number',
            'slot_type' => 'required|string|max:50',
            'status' => 'required|in:Available,Occupied,Maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        ParkingSlot::create($validated);

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot added successfully!');
    }

    public function edit(ParkingSlot $parking_slot)
    {
        return view('parking_slots.edit', ['slot' => $parking_slot]);
    }

    public function update(Request $request, ParkingSlot $parking_slot)
    {
        $validated = $request->validate([
            'slot_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('parking_slots', 'slot_number')->ignore($parking_slot->id),
            ],
            'slot_type' => 'required|string|max:50',
            'status' => 'required|in:Available,Occupied,Maintenance',
            'location' => 'nullable|string|max:255',
        ]);

        $parking_slot->update($validated);

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot updated successfully!');
    }

    public function destroy(ParkingSlot $parking_slot)
    {
        if ($parking_slot->parkingSessions()->exists()) {
            return redirect()->route('parking-slots.index')
                ->with('error', 'Cannot delete a slot that has parking sessions.');
        }

        $parking_slot->delete();

        return redirect()->route('parking-slots.index')
            ->with('success', 'Parking slot deleted successfully!');
    }
}