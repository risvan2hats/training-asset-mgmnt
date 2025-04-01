@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Location</h1>
        <a href="{{ route('locations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </div>

    <form action="{{ route('locations.store') }}" method="POST">
        @csrf
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Location Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" 
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address *</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="floor_number" class="form-label">Floor Number</label>
                            <input type="text" class="form-control @error('floor_number') is-invalid @enderror" 
                                   id="floor_number" name="floor_number" 
                                   value="{{ old('floor_number') }}">
                            @error('floor_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="country_code" class="form-label">Country *</label>
                            <select class="form-select @error('country_code') is-invalid @enderror" 
                                    id="country_code" name="country_code" required>
                                <option value="" disabled selected>Select a country</option>
                                @foreach(['US' => 'United States', 'UK' => 'United Kingdom', 'IN' => 'India', 'CA' => 'Canada'] as $code => $name)
                                    <option value="{{ $code }}" 
                                        @selected(old('country_code') == $code)>
                                        {{ $name }} ({{ $code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('country_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create Location
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
