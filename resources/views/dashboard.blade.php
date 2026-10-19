
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

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2>ParkEase</h2>
            <p class="text-muted mb-0">Parking Management Dashboard</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('register-parking.create') }}" class="btn btn-primary">Register Parking</a>
            <a href="{{ route('parking-slots.create') }}" class="btn btn-success">Create Parking Slot</a>
        </div>
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

    @if(isset($parkingSession) && $parkingSession)
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h5 class="mb-0">Edit Parking Record #{{ $parkingSession->id }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('parking-sessions.update', $parkingSession->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <h6>Owner Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input name="first_name" class="form-control" required value="{{ old('first_name', $parkingSession->vehicle->owner->first_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input name="last_name" class="form-control" required value="{{ old('last_name', $parkingSession->vehicle->owner->last_name ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input name="contact_no" class="form-control" required value="{{ old('contact_no', $parkingSession->vehicle->owner->contact_no ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $parkingSession->vehicle->owner->email ?? '') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <input name="address" class="form-control" value="{{ old('address', $parkingSession->vehicle->owner->address ?? '') }}">
                        </div>
                    </div>

                    <h6>Vehicle Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Plate Number</label>
                            <input name="plate_number" class="form-control" required value="{{ old('plate_number', $parkingSession->vehicle->plate_number ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Vehicle Type</label>
                            <input name="vehicle_type" class="form-control" required value="{{ old('vehicle_type', $parkingSession->vehicle->vehicle_type ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Brand</label>
                            <input name="brand" class="form-control" required value="{{ old('brand', $parkingSession->vehicle->brand ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Model</label>
                            <input name="model" class="form-control" required value="{{ old('model', $parkingSession->vehicle->model ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Color</label>
                            <input name="color" class="form-control" required value="{{ old('color', $parkingSession->vehicle->color ?? '') }}">
                        </div>
                    </div>

                    <h6>Parking Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Parking Slot</label>
                            <select name="parking_slot_id" class="form-select" required>
                                @foreach($parkingSlots as $slot)
                                    <option value="{{ $slot->id }}" @selected(old('parking_slot_id', $parkingSession->parking_slot_id) == $slot->id)>
                                        {{ $slot->slot_number }} ({{ $slot->status }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Entry Time</label>
                            <input type="datetime-local" name="entry_time" class="form-control" required value="{{ old('entry_time', $parkingSession->entry_time ? \Carbon\Carbon::parse($parkingSession->entry_time)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Exit Time</label>
                            <input type="datetime-local" name="exit_time" class="form-control" value="{{ old('exit_time', $parkingSession->exit_time ? \Carbon\Carbon::parse($parkingSession->exit_time)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Duration</label>
                            <input class="form-control" readonly value="{{ $parkingSession->duration_minutes !== null ? $parkingSession->duration_minutes . ' minutes' : 'Not checked out yet' }}">
                            <small class="text-muted">Duration updates when you save the entry and exit times.</small>
                        </div>
                    </div>

                    <h6>Payment Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Amount</label>
                            <input type="number" name="amount" min="0" step="0.01" class="form-control" value="{{ old('amount', $parkingSession->payment->amount ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $parkingSession->payment?->payment_date ? \Carbon\Carbon::parse($parkingSession->payment->payment_date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="">Select Method</option>
                                @foreach(['Cash', 'GCash', 'Card', 'Bank Transfer'] as $method)
                                    <option value="{{ $method }}" @selected(old('payment_method', $parkingSession->payment->payment_method ?? '') === $method)>{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="">Select Status</option>
                                @foreach(['Paid', 'Pending', 'Unpaid'] as $status)
                                    <option value="{{ $status }}" @selected(old('payment_status', $parkingSession->payment->payment_status ?? '') === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">Save All Changes</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-4">
        @foreach([
            'Owners' => $totalOwners,
            'Vehicles' => $totalVehicles,
            'Parking Slots' => $totalParkingSlots,
            'Sessions' => $totalParkingSessions,
            'Payments' => $totalPayments
        ] as $label => $total)
            <div class="col-md">
                <div class="card p-3">
                    <div class="text-muted">{{ $label }}</div>
                    <h3>{{ $total }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Connected Parking Records</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Owner</th><th>Contact</th><th>Vehicle</th>
                        <th>Plate Number</th><th>Slot</th><th>Entry Time</th>
                        <th>Exit Time</th><th>Duration</th><th>Session</th>
                        <th>Amount</th><th>Payment</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($parkingRecords as $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->vehicle?->owner?->first_name }} {{ $record->vehicle?->owner?->last_name }}</td>
                            <td>{{ $record->vehicle?->owner?->contact_no ?? '—' }}</td>
                            <td>{{ $record->vehicle?->brand }} {{ $record->vehicle?->model }}</td>
                            <td>{{ $record->vehicle?->plate_number }}</td>
                            <td>{{ $record->parkingSlot?->slot_number }}</td>
                            <td>{{ $record->entry_time }}</td>
                            <td>{{ $record->exit_time ?? 'Not exited' }}</td>
                            <td>{{ $record->duration_minutes !== null ? $record->duration_minutes . ' min' : '—' }}</td>
                            <td>
                                <span class="badge {{ $record->status === 'Active' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $record->status }}</span>
                            </td>
                            <td>{{ $record->payment ? number_format($record->payment->amount, 2) : '—' }}</td>
                            <td>{{ $record->payment?->payment_status ?? 'Not recorded' }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('parking-sessions.edit', $record->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('parking-sessions.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this parking record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="13" class="text-center p-4">No parking records found. Click Register Parking to add one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
