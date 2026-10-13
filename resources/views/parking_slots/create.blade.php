
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Parking Slot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Add Parking Slot</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('parking-slots.store') }}" method="POST" class="card p-4">
        @csrf

        <label class="form-label">Slot Number</label>
        <input type="text" name="slot_number" class="form-control mb-3" value="{{ old('slot_number') }}" required>

        <label class="form-label">Slot Type</label>
        <select name="slot_type" class="form-select mb-3" required>
            <option value="">Select Type</option>
            <option value="Regular">Regular</option>
            <option value="Reserved">Reserved</option>
            <option value="PWD">PWD</option>
            <option value="Motorcycle">Motorcycle</option>
        </select>

        <label class="form-label">Status</label>
        <select name="status" class="form-select mb-3" required>
            <option value="Available">Available</option>
            <option value="Occupied">Occupied</option>
            <option value="Maintenance">Maintenance</option>
        </select>

        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control mb-3" value="{{ old('location') }}">

        <div>
            <button type="submit" class="btn btn-primary">Save Slot</button>
            <a href="{{ route('parking-slots.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
