
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Owner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1>Edit Owner</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('owners.update', $owner->id) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <label class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control mb-3" value="{{ old('first_name', $owner->first_name) }}" required>

        <label class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control mb-3" value="{{ old('last_name', $owner->last_name) }}" required>

        <label class="form-label">Contact Number</label>
        <input type="text" name="contact_no" class="form-control mb-3" value="{{ old('contact_no', $owner->contact_no) }}" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control mb-3" value="{{ old('email', $owner->email) }}">

        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control mb-3" value="{{ old('address', $owner->address) }}">

        <div>
            <button type="submit" class="btn btn-primary">Update Owner</button>
            <a href="{{ route('owners.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

</body>
</html>
