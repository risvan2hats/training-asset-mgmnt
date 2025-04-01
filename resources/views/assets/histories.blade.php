@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Asset History: {{ $asset->serial_no }}</h1>
        <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Asset
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-0">History Records</h5>
                </div>
                <div class="col-md-6">
                    <form method="GET" class="float-end">
                        <div class="input-group">
                            <input type="date" name="date_from" class="form-control" 
                                   value="{{ request('date_from') }}" placeholder="From Date">
                            <input type="date" name="date_to" class="form-control" 
                                   value="{{ request('date_to') }}" placeholder="To Date">
                            <button class="btn btn-outline-light" type="submit">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                        @foreach($histories as $history)
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
            <div class="d-flex justify-content-center mt-4">
                {{ $histories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection