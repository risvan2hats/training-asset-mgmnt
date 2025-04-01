@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Locations</h1>
        <a href="{{ route('locations.create') }}" class="btn btn-primary">Add New Location</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="locations-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Floor #</th>
                        <th>Country</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                    <tr>
                        <td>{{ $location->name }}</td>
                        <td>{{ $location->address }}</td>
                        <td>{{ $location->floor_number ?? 'N/A' }}</td>
                        <td>{{ $location->country_code }}</td>
                        <td>
                            <a href="{{ route('locations.show', $location->id) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $locations->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection