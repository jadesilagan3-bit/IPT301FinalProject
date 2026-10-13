<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Add Payment</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('payments.store') }}" method="POST" class="card p-4">
        @csrf

        <label class="form-label">Parking Session</label>
        <select name="parking_session_id" class="form-select mb-3" required>
            <option value="">Select Session</option>
            @foreach($parkingSessions as $session)
                <option value="{{ $session->id }}">
                    Session {{ $session->id }} - {{ $session->vehicle->plate_number ?? 'No vehicle' }}
                </option>
            @endforeach
        </select>

        <label class="form-label">Amount (₱)</label>
        <input type="number" name="amount" class="form-control mb-3" min="0" step="0.01" value="{{ old('amount') }}" required>

        <label class="form-label">Payment Date</label>
        <input type="datetime-local" name="payment_date" class="form-control mb-3" value="{{ old('payment_date') }}">

        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select mb-3" required>
            <option value="">Select Method</option>
            <option value="Cash">Cash</option>
            <option value="GCash">GCash</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
        </select>

        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-select mb-3" required>
            <option value="Pending">Pending</option>
            <option value="Paid">Paid</option>
            <option value="Failed">Failed</option>
        </select>

        <div>
            <button type="submit" class="btn btn-primary">Save Payment</button>
            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
