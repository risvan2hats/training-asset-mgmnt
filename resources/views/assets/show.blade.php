@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asset Details</h1>
        <div>
            <a href="{{ route('assets.edit', $asset->id) }}" 
               class="btn btn-primary {{ $asset->status === 'Deleted' ? 'disabled no-drop' : '' }}" 
               title="{{ $asset->status === 'Deleted' ? 'Editing is disabled for deleted assets' : 'Edit' }}">
                <i class="fas fa-edit"></i> Edit
            </a>
    
            <a href="{{ route('assets.move-form', $asset->id) }}" 
               class="btn btn-warning {{ $asset->status === 'Deleted' ? 'disabled no-drop' : '' }}" 
               title="{{ $asset->status === 'Deleted' ? 'Moving is disabled for deleted assets' : 'Move' }}">
                <i class="fas fa-truck-moving"></i> Move
            </a>
    
            <a href="{{ route('assets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>   
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Serial Number:</strong> {{ $asset->serial_no }}</p>
                    <p><strong>Type:</strong> {{ $asset->asset_type }}</p>
                    <p><strong>Hardware Standard:</strong> {{ $asset->hardware_standard }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $asset->status === 'Active' ? 'success' : ($asset->status === 'In Maintenance' ? 'warning' : 'danger') }}">
                            {{ $asset->status }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Location:</strong> {{ $asset->location->name ?? 'N/A' }}</p>
                    <p><strong>Assigned To:</strong> {{ $asset->assignedUser->name ?? 'Unassigned' }}</p>
                    <p><strong>Value:</strong> {{ config('app.currency') }}{{ number_format($asset->asset_value, 2) }}</p>
                    <p><strong>Country:</strong> {{ $asset->country_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Asset History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Action</th>
                            <th>User</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asset->histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ ucfirst($history->action) }}</td>
                            <td>{{ $history->user->name }}</td>
                            <td>{{ ucfirst($history->action_notes) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection