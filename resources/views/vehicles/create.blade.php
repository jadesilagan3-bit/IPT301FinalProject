
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Vehicle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1>Add Vehicle</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('vehicles.store') }}" method="POST" class="card p-4">
        @csrf

        <label class="form-label">Plate Number</label>
        <input type="text" name="plate_number" class="form-control mb-3" value="{{ old('plate_number') }}" required>

        <label class="form-label">Vehicle Type</label>
        <select name="vehicle_type" class="form-select mb-3" required>
            <option value="">Select Type</option>
            <option value="Motorcycle">Motorcycle</option>
            <option value="Car">Car</option>
            <option value="Van">Van</option>
            <option value="Truck">Truck</option>
            <option value="SUV">SUV</option>
        </select>

        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control mb-3" required>

        <label class="form-label">Model</label>
        <input type="text" name="model" class="form-control mb-3" required>

        <label class="form-label">Color</label>
        <input type="text" name="color" class="form-control mb-3" required>

        <label class="form-label">Owner</label>
        <select name="owner_id" class="form-select mb-3" required>
            <option value="">Select Owner</option>
            @foreach($owners as $owner)
                <option value="{{ $owner->id }}">{{ $owner->first_name }} {{ $owner->last_name }}</option>
            @endforeach
        </select>

        <div>
            <button type="submit" class="btn btn-primary">Save Vehicle</button>
            <a href="{{ route('vehicles.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
