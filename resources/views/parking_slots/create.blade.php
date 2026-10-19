@extends('layouts.app')

@section('title', 'Add Parking Slot')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Add Parking Slot</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('parking-slots.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Slot Number</label>
                <input type="text" name="slot_number"
                    class="form-control" value="{{ old('slot_number') }}"
                    placeholder="Example: A-01" required>
                @error('slot_number')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slot Type</label>
                <select name="slot_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="Car" {{ old('slot_type') == 'Car' ? 'selected' : '' }}>Car</option>
                    <option value="Motorcycle" {{ old('slot_type') == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
                    <option value="PWD" {{ old('slot_type') == 'PWD' ? 'selected' : '' }}>PWD</option>
                </select>
                @error('slot_type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Available" selected>Available</option>
                    <option value="Occupied">Occupied</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location"
                    class="form-control" value="{{ old('location') }}"
                    placeholder="Example: Ground Floor">
                @error('location')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save Slot</button>
            <a href="{{ route('parking-slots.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection