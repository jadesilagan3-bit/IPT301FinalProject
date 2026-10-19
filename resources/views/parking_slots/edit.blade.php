@extends('layouts.app')

@section('title', 'Edit Parking Slot')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Parking Slot</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('parking-slots.update', $slot) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Slot Number</label>
                <input type="text" name="slot_number"
                    class="form-control"
                    value="{{ old('slot_number', $slot->slot_number) }}" required>
                @error('slot_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slot Type</label>
                <select name="slot_type" class="form-select" required>
                    @foreach (['Car', 'Motorcycle', 'PWD'] as $type)
                        <option value="{{ $type }}"
                            {{ old('slot_type', $slot->slot_type) == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @foreach (['Available', 'Occupied', 'Maintenance'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $slot->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control"
                    value="{{ old('location', $slot->location) }}">
            </div>

            <button type="submit" class="btn btn-primary">Update Slot</button>
            <a href="{{ route('parking-slots.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection