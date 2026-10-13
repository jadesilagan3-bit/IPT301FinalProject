
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Slots</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Parking Management System</h1>
    <h3>Parking Slots</h3>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Dashboard</a>
    <a href="{{ route('parking-slots.create') }}" class="btn btn-primary mb-3">Add Slot</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Slot Number</th>
                <th>Type</th>
                <th>Status</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parkingSlots as $slot)
                <tr>
                    <td>{{ $slot->slot_number }}</td>
                    <td>{{ $slot->slot_type }}</td>
                    <td>{{ $slot->status }}</td>
                    <td>{{ $slot->location }}</td>
                    <td>
                        <a href="{{ route('parking-slots.edit', $slot->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('parking-slots.destroy', $slot->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this parking slot?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No parking slots found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
