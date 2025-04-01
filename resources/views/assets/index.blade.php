@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asset Management</h1>
        <a href="{{ route('assets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Asset
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">All Assets</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="float-end">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Serial No</th>
                            <th>Type</th>
                            <th>Hardware</th>
                            <th>Location</th>
                            <th>Assigned To</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                        <tr>
                            <td>{{ $asset->serial_no }}</td>
                            <td>{{ $asset->asset_type }}</td>
                            <td>{{ $asset->hardware_standard }}</td>
                            <td>{{ $asset->location->name ?? 'N/A' }}</td>
                            <td>{{ $asset->assignedUser->name ?? 'Unassigned' }}</td>
                            <td>{{ config('app.currency') }}{{ number_format($asset->asset_value, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $asset->status === 'Active' ? 'success' : ($asset->status === 'In Maintenance' ? 'warning' : 'danger') }}">
                                    {{ $asset->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('assets.edit', $asset->id) }}" 
                                       class="btn btn-sm btn-primary {{ $asset->status === 'Deleted' ? 'disabled no-drop' : '' }}" 
                                       title="{{ $asset->status === 'Deleted' ? 'Editing is disabled for deleted assets' : 'Edit' }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                            
                                    <a href="{{ route('assets.move-form', $asset->id) }}" 
                                       class="btn btn-sm btn-warning {{ $asset->status === 'Deleted' ? 'disabled no-drop' : '' }}" 
                                       title="{{ $asset->status === 'Deleted' ? 'Moving is disabled for deleted assets' : 'Move' }}">
                                        <i class="fas fa-truck-moving"></i>
                                    </a>
                            
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger {{ $asset->status === 'Deleted' ? 'disabled no-drop' : '' }}" 
                                                title="{{ $asset->status === 'Deleted' ? 'Deletion is disabled for deleted assets' : 'Delete' }}"
                                                {{ $asset->status === 'Deleted' ? 'disabled' : '' }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>                                                    
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No assets found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $assets->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection