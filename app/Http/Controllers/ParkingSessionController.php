<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Payment;
use App\Models\ParkingSession;
use App\Models\ParkingSlot;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

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
            'parkingSession',
            'vehicles',
            'parkingSlots',
            'totalOwners',
            'totalVehicles',
            'totalParkingSlots',
            'totalParkingSessions',
            'totalPayments',
            'parkingRecords'
        ));
    }

    public function update(Request $request, $id)
    {
        $session = ParkingSession::with('vehicle.owner', 'payment')
            ->findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_no' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',

            'plate_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('vehicles', 'plate_number')->ignore(
                    $session->vehicle_id
                ),
            ],
            'vehicle_type' => 'required|string|max:100',
            'brand' => 'required|string|max:100',
            'model' => 'required|string|max:100',
            'color' => 'required|string|max:100',

            'parking_slot_id' => 'required|exists:parking_slots,id',
            'entry_time' => 'required|date',
            'exit_time' => 'nullable|date|after_or_equal:entry_time',

            'amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_method' => 'nullable|string|max:100',
            'payment_status' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $session = ParkingSession::with('vehicle.owner', 'payment')
                ->lockForUpdate()
                ->findOrFail($id);

            $vehicle = $session->vehicle;

            if (!$vehicle) {
                throw ValidationException::withMessages([
                    'plate_number' => 'The vehicle linked to this parking record could not be found.',
                ]);
            }

            $owner = $vehicle->owner;

            if (!$owner) {
                throw ValidationException::withMessages([
                    'first_name' => 'The owner linked to this vehicle could not be found.',
                ]);
            }

            $oldSlot = ParkingSlot::whereKey($session->parking_slot_id)
                ->lockForUpdate()
                ->firstOrFail();

            $newSlot = ParkingSlot::whereKey($validated['parking_slot_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $isCompleted = !empty($validated['exit_time']);
            $isChangingSlot = (int) $newSlot->id !== (int) $oldSlot->id;

            if (
                !$isCompleted &&
                $isChangingSlot &&
                $newSlot->status !== 'Available'
            ) {
                throw ValidationException::withMessages([
                    'parking_slot_id' => 'The selected parking slot is not available.',
                ]);
            }

            $owner->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'contact_no' => $validated['contact_no'],
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            $vehicle->update([
                'plate_number' => $validated['plate_number'],
                'vehicle_type' => $validated['vehicle_type'],
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'color' => $validated['color'],
            ]);

            if ($isChangingSlot) {
                $oldSlot->update([
                    'status' => 'Available',
                ]);
            }

            $session->parking_slot_id = $newSlot->id;
            $session->entry_time = $validated['entry_time'];
            $session->exit_time = $validated['exit_time'] ?? null;

            if ($session->exit_time) {
                $session->duration_minutes = Carbon::parse(
                    $session->entry_time
                )->diffInMinutes(
                    Carbon::parse($session->exit_time)
                );

                $session->status = 'Completed';

                $newSlot->update([
                    'status' => 'Available',
                ]);
            } else {
                $session->duration_minutes = null;
                $session->status = 'Active';

                $newSlot->update([
                    'status' => 'Occupied',
                ]);
            }

            $session->save();

            $hasPaymentDetails =
                ($validated['amount'] ?? null) !== null ||
                !empty($validated['payment_date']) ||
                !empty($validated['payment_method']) ||
                !empty($validated['payment_status']) ||
                $session->payment !== null;

            if ($hasPaymentDetails) {
                $paymentData = [
                    'amount' => $validated['amount']
                        ?? $session->payment?->amount
                        ?? 0,

                    'payment_date' => $validated['payment_date']
                        ?? $session->payment?->payment_date,

                    'payment_method' => $validated['payment_method']
                        ?? $session->payment?->payment_method,

                    'payment_status' => $validated['payment_status']
                        ?? $session->payment?->payment_status
                        ?? 'Paid',
                ];

                $session->payment()->updateOrCreate(
                    ['parking_session_id' => $session->id],
                    $paymentData
                );
            }
        });

        return redirect()->route('dashboard')
            ->with(
                'success',
                'Parking record and its details updated successfully.'
            );
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $session = ParkingSession::findOrFail($id);

            if ($session->payment()->exists()) {
                throw ValidationException::withMessages([
                    'session' => 'This session has a payment record and cannot be deleted.',
                ]);
            }

            if ($session->status === 'Active') {
                ParkingSlot::whereKey($session->parking_slot_id)
                    ->update([
                        'status' => 'Available',
                    ]);
            }

            $session->delete();
        });

        return redirect()->route('dashboard')
            ->with('success', 'Parking record deleted successfully.');
    }
}
