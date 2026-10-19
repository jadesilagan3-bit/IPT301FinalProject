<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Parking - ParkEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">ParkEase</a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a>
    </div>
</nav>

<div class="container py-4" style="max-width: 900px">

    <h2>Register Parking</h2>
    <p class="text-muted">Register an owner, vehicle, and parking session in one form.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register-parking.store') }}" method="POST">
        @csrf

        <div class="card mb-4">
            <div class="card-header fw-bold">1. Owner Information</div>
            <div class="card-body">

                <label for="owner_option" class="form-label">Owner Option</label>
                <select name="owner_option" id="owner_option" class="form-select mb-3" required>
                    <option value="existing" {{ old('owner_option', 'existing') === 'existing' ? 'selected' : '' }}>Select Existing Owner</option>
                    <option value="new" {{ old('owner_option') === 'new' ? 'selected' : '' }}>Register New Owner</option>
                </select>

                <div id="existingOwner">
                    <label for="owner_id" class="form-label">Select Owner</label>
                    <select name="owner_id" id="owner_id" class="form-select">
                        <option value="">Choose an owner</option>
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" {{ (string) old('owner_id') === (string) $owner->id ? 'selected' : '' }}>
                                {{ $owner->first_name }} {{ $owner->last_name }} (ID: {{ $owner->id }})
                            </option>
                        @endforeach
                    </select>

                    @if($owners->isEmpty())
                        <p class="text-danger small mt-2">No owners registered yet. Choose Register New Owner.</p>
                    @endif
                </div>

                <div id="newOwner" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_no" class="form-control" value="{{ old('contact_no') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-bold">2. Vehicle Information</div>
            <div class="card-body row g-3">

                <div class="col-md-6">
                    <label class="form-label">Plate Number</label>
                    <input type="text" name="plate_number" class="form-control" value="{{ old('plate_number') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Vehicle Type</label>
                    <select name="vehicle_type" class="form-select" required>
                        <option value="">Choose type</option>
                        <option value="Car" {{ old('vehicle_type') === 'Car' ? 'selected' : '' }}>Car</option>
                        <option value="Motorcycle" {{ old('vehicle_type') === 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                        <option value="Van" {{ old('vehicle_type') === 'Van' ? 'selected' : '' }}>Van</option>
                        <option value="Truck" {{ old('vehicle_type') === 'Truck' ? 'selected' : '' }}>Truck</option>
                        <option value="Other" {{ old('vehicle_type') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Brand</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Color</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color') }}" required>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-bold">3. Parking Information</div>
            <div class="card-body">

                <label for="parking_slot_id" class="form-label">Available Parking Slot</label>
                <select name="parking_slot_id" id="parking_slot_id" class="form-select" required>
                    <option value="">Choose an available slot</option>
                    @foreach($parkingSlots as $slot)
                        <option value="{{ $slot->id }}" {{ (string) old('parking_slot_id') === (string) $slot->id ? 'selected' : '' }}>
                            {{ $slot->slot_number }} - {{ $slot->slot_type }} - {{ $slot->location }}
                        </option>
                    @endforeach
                </select>

                @if($parkingSlots->isEmpty())
                    <p class="text-danger mt-2 mb-0">No available parking slots. Please add or free a slot first.</p>
                @endif

                <p class="text-muted small mt-2 mb-0">
                    The entry time will be recorded automatically. The slot will become Occupied after registration.
                </p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg" {{ $parkingSlots->isEmpty() ? 'disabled' : '' }}>
                Register Parking
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">Cancel</a>
        </div>

    </form>
</div>

<script>
    const ownerOption = document.getElementById('owner_option');
    const existingOwner = document.getElementById('existingOwner');
    const newOwner = document.getElementById('newOwner');
    const ownerSelect = document.getElementById('owner_id');

    function toggleOwnerFields() {
        const isNew = ownerOption.value === 'new';

        existingOwner.style.display = isNew ? 'none' : 'block';
        newOwner.style.display = isNew ? 'flex' : 'none';

        ownerSelect.required = !isNew;

        newOwner.querySelectorAll('input').forEach(function (input) {
            input.required = isNew &&
                ['first_name', 'last_name', 'contact_no'].includes(input.name);
        });
    }

    ownerOption.addEventListener('change', toggleOwnerFields);
    toggleOwnerFields();
</script>

</body>
</html>
```