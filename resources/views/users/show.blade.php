@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Details: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">User Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Employee ID:</strong> {{ $user->employee_id }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Position:</strong> {{ $user->position }}</p>
                    <p><strong>Hire Date:</strong> {{ $user->hire_date->format('Y-m-d') }}</p>
                    <p><strong>Country:</strong> {{ $user->country_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assigned Assets</h5>
        </div>
        <div class="card-body">
            @if($user->assets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->assets as $asset)
                            <tr>
                                <td>{{ $asset->serial_no }}</td>
                                <td>{{ $asset->asset_type }}</td>
                                <td>{{ $asset->location->name }}</td>
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
                <p>No assets assigned to this user.</p>
            @endif
        </div>
    </div>
</div>
@endsection