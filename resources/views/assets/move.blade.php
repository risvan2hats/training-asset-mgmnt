@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Move Asset: {{ $asset->serial_no }}</h1>
        <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </div>

    <form action="{{ route('assets.move', $asset->id) }}" method="POST">
        @csrf
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Move Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Current Location</label>
                            <input type="text" class="form-control" value="{{ $asset->location->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location_id" class="form-label">New Location</label>
                            <select class="form-control" id="location_id" name="location_id" required>
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" 
                                        @if($location->id == $asset->location->id) disabled @endif>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-truck-moving"></i> Move Asset
                </button>
            </div>
        </div>
    </form>
</div>
@endsection