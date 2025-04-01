@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Asset</h1>
        <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </div>

    <form action="{{ route('assets.update', $asset->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Asset Information</h5>
            </div>
            <div class="card-body">
                @include('assets.form', ['asset' => $asset])
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Asset
                </button>
            </div>
        </div>
    </form>
</div>
@endsection