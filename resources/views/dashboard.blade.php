```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ParkEase Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>ParkEase</h2>
            <p class="text-muted mb-0">Parking Management Dashboard</p>
        </div>
        <a href="{{ route('register-parking.create') }}" class="btn btn-primary">
            Register Parking
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="card p-3">
                <div class="text-muted">Owners</div>
                <h3>{{ $totalOwners }}</h3>
            </div>
        </div>
        <div class="col-md">
            <div class="card p-3">
                <div class="text-muted">Vehicles</div>
                <h3>{{ $totalVehicles }}</h3>
            </div>
        </div>
        <div class="col-md">
            <div class="card p-3">
                <div class="text-muted">Parking Slots</div>
                <h3>{{ $totalParkingSlots }}</h3>
            </div>
        </div>
        <div class="col-md">
            <div class="card p-3">
                <div class="text-muted">Sessions</div>
                <h3>{{ $totalParkingSessions }}</h3>
            </div>
        </div>
        <div class="col-md">
            <div class="card p-3">
                <div class="text-muted">Payments</div>
                <h3>{{ $totalPayments }}</h3>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Connected Parking Records</h5>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Contact</th>
                        <th>Vehicle</th>
                        <th>Plate Number</th>
                        <th>Slot</th>
                        <th>Entry Time</th>
                        <th>Exit Time</th>
                        <th>Duration</th>
                        <th>Session</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parkingRecords as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>
                                {{ $record->vehicle?->owner?->first_name }}
                                {{ $record->vehicle?->owner?->last_name }}
                            </td>
                            <td>{{ $record->vehicle?->owner?->contact_no ?? '—' }}</td>
                            <td>
                                {{ $record->vehicle?->brand }}
                                {{ $record->vehicle?->model }}
                            </td>
                            <td>{{ $record->vehicle?->plate_number }}</td>
                            <td>{{ $record->parkingSlot?->slot_number }}</td>
                            <td>{{ $record->entry_time }}</td>
                            <td>{{ $record->exit_time ?? 'Not exited' }}</td>
                            <td>
                                {{ $record->duration_minutes !== null
                                    ? $record->duration_minutes . ' min'
                                    : '—' }}
                            </td>
                            <td>
                                <span class="badge {{ $record->status === 'Active' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td>
                                {{ $record->payment ? number_format($record->payment->amount, 2) : '—' }}
                            </td>
                            <td>{{ $record->payment?->payment_status ?? 'Not recorded' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('parking-sessions.edit', $record->id) }}"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="{{ route('parking-sessions.destroy', $record->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this parking record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center p-4">
                                No parking records found. Click Register Parking to add one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
