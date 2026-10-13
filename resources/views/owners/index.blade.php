
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owners</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1>Parking Management System</h1>
    <h3>Owners</h3>

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Dashboard</a>
    <a href="{{ route('owners.create') }}" class="btn btn-primary mb-3">Add Owner</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($owners as $owner)
                <tr>
                    <td>{{ $owner->id }}</td>
                    <td>{{ $owner->first_name }}</td>
                    <td>{{ $owner->last_name }}</td>
                    <td>{{ $owner->contact_no }}</td>
                    <td>{{ $owner->email }}</td>
                    <td>
                        <a href="{{ route('owners.edit', $owner->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('owners.destroy', $owner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this owner?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No owners found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
