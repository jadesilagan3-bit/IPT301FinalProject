```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Parking Record - ParkEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card mx-auto" style="max-width: 650px;">
        <div class="card-header">
            <h4 class="mb-0">Edit Parking Record</h4>
        </div>

        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('parking-sessions.update', $parkingSession->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Vehicle</label>
                    <select name="vehicle_id" class="form-select" required>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}"
                                {{ old('vehicle_id', $parkingSession->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                {{ $vehicle->plate_number }} -
                                {{ $vehicle->brand }} {{ $vehicle->model }}
                                ({{ $vehicle->owner?->first_name }} {{ $vehicle->owner?->last_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Parking Slot</label>
                    <select name="parking_slot_id" class="form-select" required>
                        @foreach($parkingSlots as $slot)
                            <option value="{{ $slot->id }}"
                                {{ old('parking_slot_id', $parkingSession->parking_slot_id) == $slot->id ? 'selected' : '' }}>
                                {{ $slot->slot_number }} - {{ $slot->status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Entry Time</label>
                    <input
                        type="datetime-local"
                        name="entry_time"
                        class="form-control"
                        value="{{ old('entry_time', $parkingSession->entry_time ? \Carbon\Carbon::parse($parkingSession->entry_time)->format('Y-m-d\TH:i') : '') }}"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Session Status</label>
                    <input type="text" class="form-control" value="{{ $parkingSession->status }}" disabled>
                    <small class="text-muted">Use the vehicle exit process to finish an active session.</small>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>
```