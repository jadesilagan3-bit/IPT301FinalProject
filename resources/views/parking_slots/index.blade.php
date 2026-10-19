@extends('layouts.app')

@section('title', 'Parking Slots')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Parking Slots</h2>
    <a href="{{ route('parking-slots.create') }}" class="btn btn-primary">
        + Add Slot
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        Please check the form for errors.
    </div>
@endif

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Slot Number</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($slots as $slot)
                    <tr>
                        <td>{{ $slot->slot_number }}</td>
                        <td>{{ $slot->slot_type }}</td>
                        <td>{{ $slot->status }}</td>
                        <td>{{ $slot->location ?? 'Not specified' }}</td>
                        <td>
                            <a href="{{ route('parking-slots.edit', $slot) }}"
                                class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('parking-slots.destroy', $slot) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this slot?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            No parking slots added yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection