@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Location Details: {{ $location->name }}</h1>
        <div>
            <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('locations.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Location Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $location->name }}</p>
                    <p><strong>Address:</strong> {{ $location->address }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Floor Number:</strong> {{ $location->floor_number ?? 'N/A' }}</p>
                    <p><strong>Country:</strong> {{ $location->country_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assets at this Location</h5>
        </div>
        <div class="card-body">
            @if($location->assets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Type</th>
                                <th>Assigned To</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($location->assets as $asset)
                            <tr>
                                <td>{{ $asset->serial_no }}</td>
                                <td>{{ $asset->asset_type }}</td>
                                <td>{{ $asset->assignedUser->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge bg-{{ $asset->status === 'Active' ? 'success' : ($asset->status === 'In Maintenance' ? 'warning' : 'danger') }}">
                                        {{ $asset->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No assets currently at this location.</p>
            @endif
        </div>
    </div>
</div>
@endsection