
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Edit Vehicle</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('vehicles.update', $vehicle->id) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <label class="form-label">Plate Number</label>
        <input type="text" name="plate_number" class="form-control mb-3" value="{{ old('plate_number', $vehicle->plate_number) }}" required>

        <label class="form-label">Vehicle Type</label>
        <select name="vehicle_type" class="form-select mb-3" required>
            @foreach(['Motorcycle', 'Car', 'Van', 'Truck', 'SUV'] as $type)
                <option value="{{ $type }}" {{ old('vehicle_type', $vehicle->vehicle_type) == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>

        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control mb-3" value="{{ old('brand', $vehicle->brand) }}" required>

        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control mb-3" value="{{ old('model', $vehicle->model) }}" required>

        <label class="form-label">Color</label>
        <input type="text" name="color" class="form-control mb-3" value="{{ old('color', $vehicle->color) }}" required>

        <label class="form-label">Owner</label>
        <select name="owner_id" class="form-select mb-3" required>
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}" {{ old('owner_id', $vehicle->owner_id) == $owner->id ? 'selected' : '' }}>
                    {{ $owner->first_name }} {{ $owner->last_name }}
                </option>
            @endforeach
        </select>

        <div>
            <button type="submit" class="btn btn-primary">Update Vehicle</button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
