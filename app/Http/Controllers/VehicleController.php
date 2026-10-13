<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Owner;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('owner')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $owners = Owner::all();
        return view('vehicles.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'owner_id' => 'required|exists:owners,id',
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle added successfully.');
    }

    public function edit(Vehicle $vehicle)
    {
        $owners = Owner::all();
        return view('vehicles.edit', compact('vehicle', 'owners'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $vehicle->id,
            'vehicle_type' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'owner_id' => 'required|exists:owners,id',
        ]);

        $vehicle->update($data);

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->parkingSessions()->exists()) {
            return back()->with('error', 'Cannot delete a vehicle with parking session records.');
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('success', 'Vehicle deleted successfully.');
    }
}
