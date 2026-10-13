
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Sessions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Parking Management System</h1>
    <h3>Parking Sessions</h3>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Dashboard</a>
    <a href="{{ route('parking-sessions.create') }}" class="btn btn-primary mb-3">Add Session</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>Vehicle</th>
                <th>Parking Slot</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Duration (Minutes)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($parkingSessions as $session)
                <tr>
                    <td>{{ $session->vehicle->plate_number ?? 'N/A' }}</td>
                    <td>{{ $session->parkingSlot->slot_number ?? 'N/A' }}</td>
                    <td>{{ $session->entry_time }}</td>
                    <td>{{ $session->exit_time ?? 'Not exited' }}</td>
                    <td>{{ $session->duration_minutes ?? 'Not calculated' }}</td>
                    <td>{{ $session->status }}</td>
                    <td>
                        <a href="{{ route('parking-sessions.edit', $session->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('parking-sessions.destroy', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this session?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No parking sessions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
