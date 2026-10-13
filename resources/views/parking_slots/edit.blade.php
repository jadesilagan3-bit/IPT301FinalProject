
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parking Slot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Edit Parking Slot</h1>

    <form action="{{ route('parking-slots.update', $parkingSlot->id) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <label class="form-label">Slot Number</label>
        <input type="text" name="slot_number" class="form-control mb-3" value="{{ old('slot_number', $parkingSlot->slot_number) }}" required>

        <label class="form-label">Slot Type</label>
        <select name="slot_type" class="form-select mb-3" required>
            @foreach(['Regular', 'Reserved', 'PWD', 'Motorcycle'] as $type)
                <option value="{{ $type }}" {{ old('slot_type', $parkingSlot->slot_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>

        <label class="form-label">Status</label>
        <select name="status" class="form-select mb-3" required>
            @foreach(['Available', 'Occupied', 'Maintenance'] as $status)
                <option value="{{ $status }}" {{ old('status', $parkingSlot->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>

        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control mb-3" value="{{ old('location', $parkingSlot->location) }}">

        <div>
            <button type="submit" class="btn btn-primary">Update Slot</button>
            <a href="{{ route('parking-slots.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
