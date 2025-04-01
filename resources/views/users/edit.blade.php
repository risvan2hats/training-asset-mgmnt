@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit User: {{ $user->name }}</h1>
        <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Cancel
        </a>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">User Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="employee_id" class="form-label">Employee ID *</label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                   id="employee_id" name="employee_id" 
                                   value="{{ old('employee_id', $user->employee_id) }}" required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">Position *</label>
                            <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                   id="position" name="position" 
                                   value="{{ old('position', $user->position) }}" required>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="hire_date" class="form-label">Hire Date *</label>
                            <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                   id="hire_date" name="hire_date" 
                                   value="{{ old('hire_date', $user->hire_date->format('Y-m-d')) }}" required>
                            @error('hire_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isSuperAdmin())
                <div class="mb-3">
                    <label for="country_code" class="form-label">Country *</label>
                    <select class="form-select @error('country_code') is-invalid @enderror" 
                            id="country_code" name="country_code" required>
                        @foreach(['US' => 'United States', 'UK' => 'United Kingdom', 'IN' => 'India', 'CA' => 'Canada'] as $code => $name)
                            <option value="{{ $code }}" 
                                @selected(old('country_code', $user->country_code) == $code)>
                                {{ $name }} ({{ $code }})
                            </option>
                        @endforeach
                    </select>
                    @error('country_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
            </div>
        </div>
    </form>
</div>
@endsection