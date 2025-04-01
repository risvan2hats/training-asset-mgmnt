<div class="row mb-3">
    <!-- Left Column -->
    <div class="col-md-6">
        <!-- Serial Number -->
        <label for="serial_no" class="form-label">Serial Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('serial_no') is-invalid @enderror" 
                id="serial_no" name="serial_no" 
                value="{{ old('serial_no', $asset->serial_no ?? '') }}" required>
        @error('serial_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Asset Type -->
    <div class="col-md-6">
        <label for="asset_type" class="form-label">Asset Type <span class="text-danger">*</span></label>
        <select class="form-select @error('asset_type') is-invalid @enderror" 
                id="asset_type" name="asset_type" required>
            <option value="">Select Type</option>
            @foreach(['Laptop', 'Desktop', 'Monitor', 'Printer', 'Server', 'Phone', 'Tablet', 'Other'] as $type)
                <option value="{{ $type }}" 
                    {{ old('asset_type', $asset->asset_type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        @error('asset_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mb-3">
    <!-- Hardware Standard -->
    <div class="col-md-6">
        <label for="hardware_standard" class="form-label">Hardware Standard <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('hardware_standard') is-invalid @enderror" 
                id="hardware_standard" name="hardware_standard" 
                value="{{ old('hardware_standard', $asset->hardware_standard ?? '') }}" required>
        @error('hardware_standard')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Location -->
    <div class="col-md-6">
        <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
        <select class="form-select @error('location_id') is-invalid @enderror" 
                id="location_id" name="location_id" required>
            <option value="">Select Location</option>
            @foreach($locations as $location)
                <option value="{{ $location->id }}" 
                    {{ old('location_id', $asset->location_id ?? '') == $location->id ? 'selected' : '' }}>
                    {{ $location->name }} ({{ $location->country_code }})
                </option>
            @endforeach
        </select>
        @error('location_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mb-3">
    <!-- Assigned To -->
    <div class="col-md-6">
        <label for="assigned_to" class="form-label">Assigned To</label>
        <select class="form-select @error('assigned_to') is-invalid @enderror" 
                id="assigned_to" name="assigned_to">
            <option value="">Unassigned</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" 
                    {{ old('assigned_to', $asset->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }} ({{ $user->employee_id }})
                </option>
            @endforeach
        </select>
        @error('assigned_to')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Asset Value -->
    <div class="col-md-6">
        <label for="asset_value" class="form-label">Value <span class="text-danger">*</span></label>
        <div class="input-group">
            <span class="input-group-text">{{ config('app.currency') }}</span>
            <input type="number" step="0.01" min="0" 
                    class="form-control @error('asset_value') is-invalid @enderror" 
                    id="asset_value" name="asset_value" 
                    value="{{ old('asset_value', $asset->asset_value ?? '') }}" required>
            @error('asset_value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row mb-3">
    <!-- Warranty Expiry -->
    <div class="col-md-6">
        <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
        <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry', isset($asset->warranty_expiry) ? $asset->warranty_expiry->format('Y-m-d') : '') }}">
        @error('warranty_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <!-- Status -->
    <div class="col-md-6">
        <div class="mb-3">
            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select @error('status') is-invalid @enderror" 
                    id="status" name="status" required>
                @foreach(['Active', 'In Maintenance', 'Retired', 'Lost/Stolen'] as $status)
                    <option value="{{ $status }}" 
                        {{ old('status', $asset->status ?? 'Active') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<!-- Second Row -->
<div class="row">
    @if(auth()->user()->isSuperAdmin())
    <div class="col-md-6">
        <!-- Country Code (Visible only to Super Admin) -->
        <div class="mb-3">
            <label for="country_code" class="form-label">Country <span class="text-danger">*</span></label>
            <select class="form-select @error('country_code') is-invalid @enderror" 
                    id="country_code" name="country_code" required>
                @foreach(['US' => 'United States', 'UK' => 'United Kingdom', 'IN' => 'India', 'CA' => 'Canada'] as $code => $name)
                    <option value="{{ $code }}" 
                        {{ old('country_code', $asset->country_code ?? '') == $code ? 'selected' : '' }}>
                        {{ $name }} ({{ $code }})
                    </option>
                @endforeach
            </select>
            @error('country_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    @else
    <input type="hidden" name="country_code" value="{{ auth()->user()->country_code }}">
    @endif
</div>

<!-- Notes -->
<div class="mb-3">
    <label for="notes" class="form-label">Notes</label>
    <textarea class="form-control @error('notes') is-invalid @enderror" 
              id="notes" name="notes" rows="3">{{ old('notes', $asset->notes ?? '') }}</textarea>
    @error('notes')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers       
        flatpickr('#warranty_expiry', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        // Dynamic field requirements based on status
        const statusField = document.getElementById('status');
        const requiredFields = document.querySelectorAll('[required]');
        
        statusField.addEventListener('change', function() {
            if (this.value === 'Lost/Stolen') {
                // Make notes required for lost/stolen assets
                document.getElementById('notes').required = true;
            } else {
                document.getElementById('notes').required = false;
            }
        });
    });
</script>
@endpush