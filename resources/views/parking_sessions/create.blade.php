
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parking Session</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Add Parking Session</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('parking-sessions.store') }}" method="POST" class="card p-4">
        @csrf

        <label class="form-label">Vehicle</label>
        <select name="vehicle_id" class="form-select mb-3" required>
            <option value="">Select Vehicle</option>
            @foreach($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}">
                    {{ $vehicle->plate_number }} - {{ $vehicle->brand }} {{ $vehicle->model }}
                </option>
            @endforeach
        </select>

        <label class="form-label">Parking Slot</label>
        <select name="parking_slot_id" class="form-select mb-3" required>
            <option value="">Select Parking Slot</option>
            @foreach($parkingSlots as $slot)
                <option value="{{ $slot->id }}">{{ $slot->slot_number }} - {{ $slot->status }}</option>
            @endforeach
        </select>

        <label class="form-label">Entry Time</label>
        <input type="datetime-local" name="entry_time" class="form-control mb-3" value="{{ old('entry_time') }}" required>

        <label class="form-label">Exit Time</label>
        <input type="datetime-local" name="exit_time" class="form-control mb-3" value="{{ old('exit_time') }}">

        <label class="form-label">Duration (Minutes)</label>
        <input type="number" name="duration_minutes" class="form-control mb-3" value="{{ old('duration_minutes') }}" min="0">

        <label class="form-label">Status</label>
        <select name="status" class="form-select mb-3" required>
            <option value="Active">Active</option>
            <option value="Completed">Completed</option>
        </select>

        <div>
            <button type="submit" class="btn btn-primary">Save Session</button>
            <a href="{{ route('parking-sessions.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
