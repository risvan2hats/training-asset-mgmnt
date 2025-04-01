@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>All Asset Histories</h1>
        <div>
            <form method="GET" class="d-inline">
                <div class="input-group" style="flex-wrap: nowrap;">
                    <select name="asset_ids[]" class="form-select select2-tags" multiple="multiple" data-placeholder="Select Assets" style="width: auto">
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}" 
                                    {{ in_array($asset->id, (array)request('asset_ids', [])) ? 'selected' : '' }}>
                                {{ $asset->serial_no }} ({{ $asset->asset_type ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" class="form-control" 
                           value="{{ request('date_from') }}" placeholder="From Date">
                    <input type="date" name="date_to" class="form-control" 
                           value="{{ request('date_to') }}" placeholder="To Date">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                        <i class="fas fa-sync me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Asset</th>
                            <th>Action</th>
                            <th>User</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('assets.show', $history->asset_id) }}">
                                    {{ $history->asset->serial_no }}
                                </a>
                            </td>
                            <td>{{ ucfirst($history->action) }}</td>
                            <td>{{ $history->user->name }}</td>
                            <td>{{ ucfirst($history->action_notes) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $histories->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection