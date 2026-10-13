<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;
use App\Models\Vehicle;
use App\Models\ParkingSlot;
use App\Models\ParkingSession;
use App\Models\Payment;

class ParkEaseSeeder extends Seeder
{
    public function run(): void
    {
        $owner1 = Owner::firstOrCreate(
            ['email' => 'juan@example.com'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'contact_no' => '09123456789',
                'address' => 'Manila',
            ]
        );

        $owner2 = Owner::firstOrCreate(
            ['email' => 'maria@example.com'],
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'contact_no' => '09987654321',
                'address' => 'Quezon City',
            ]
        );

        $owner3 = Owner::firstOrCreate(
            ['email' => 'pedro@example.com'],
            [
                'first_name' => 'Pedro',
                'last_name' => 'Reyes',
                'contact_no' => '09112223334',
                'address' => 'Caloocan',
            ]
        );

        $vehicle1 = Vehicle::firstOrCreate(
            ['plate_number' => 'ABC-1234'],
            [
                'vehicle_type' => 'Car',
                'brand' => 'Toyota',
                'model' => 'Vios',
                'color' => 'White',
                'owner_id' => $owner1->id,
            ]
        );

        $vehicle2 = Vehicle::firstOrCreate(
            ['plate_number' => 'XYZ-5678'],
            [
                'vehicle_type' => 'Motorcycle',
                'brand' => 'Honda',
                'model' => 'Click',
                'color' => 'Black',
                'owner_id' => $owner2->id,
            ]
        );

        $vehicle3 = Vehicle::firstOrCreate(
            ['plate_number' => 'DEF-9012'],
            [
                'vehicle_type' => 'Car',
                'brand' => 'Mitsubishi',
                'model' => 'Mirage',
                'color' => 'Red',
                'owner_id' => $owner3->id,
            ]
        );

        $slot1 = ParkingSlot::firstOrCreate(
            ['slot_number' => 'A01'],
            [
                'slot_type' => 'Car',
                'status' => 'Occupied',
                'location' => 'Ground Floor',
            ]
        );

        $slot2 = ParkingSlot::firstOrCreate(
            ['slot_number' => 'A02'],
            [
                'slot_type' => 'Car',
                'status' => 'Available',
                'location' => 'Ground Floor',
            ]
        );

        $slot3 = ParkingSlot::firstOrCreate(
            ['slot_number' => 'B01'],
            [
                'slot_type' => 'Motorcycle',
                'status' => 'Occupied',
                'location' => 'Second Floor',
            ]
        );

        ParkingSlot::firstOrCreate(
            ['slot_number' => 'B02'],
            [
                'slot_type' => 'Motorcycle',
                'status' => 'Available',
                'location' => 'Second Floor',
            ]
        );

        ParkingSlot::firstOrCreate(
            ['slot_number' => 'C01'],
            [
                'slot_type' => 'Car',
                'status' => 'Maintenance',
                'location' => 'Third Floor',
            ]
        );

        $session1 = ParkingSession::firstOrCreate(
            [
                'vehicle_id' => $vehicle1->id,
                'parking_slot_id' => $slot1->id,
            ],
            [
                'entry_time' => now()->subHours(2),
                'exit_time' => null,
                'duration_minutes' => null,
                'status' => 'Active',
            ]
        );

        $session2 = ParkingSession::firstOrCreate(
            [
                'vehicle_id' => $vehicle2->id,
                'parking_slot_id' => $slot3->id,
            ],
            [
                'entry_time' => now()->subHours(4),
                'exit_time' => now()->subHours(2),
                'duration_minutes' => 120,
                'status' => 'Completed',
            ]
        );

        Payment::firstOrCreate(
            ['parking_session_id' => $session2->id],
            [
                'amount' => 100.00,
                'payment_date' => now()->subHours(2),
                'payment_method' => 'Cash',
                'payment_status' => 'Paid',
            ]
        );
    }
}
