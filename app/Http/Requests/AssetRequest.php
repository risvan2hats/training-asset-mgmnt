<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $assetId = $this->route('asset');
    
        return [
            'serial_no' => [
                'required',
                'string',
                'max:100',
                Rule::unique('assets')->ignore($assetId)
            ],
            'asset_type' => [
                'required',
                'string',
                Rule::in(['Laptop', 'Desktop', 'Monitor', 'Printer', 'Server', 'Phone', 'Tablet', 'Other'])
            ],
            'hardware_standard' => 'required|string|max:100',
            'location_id' => 'required|exists:locations,id',
            'assigned_to' => 'nullable|exists:users,id',
            'asset_value' => 'required|numeric|min:0|max:999999.99',
            'country_code' => 'required|string|size:2',
            'warranty_expiry' => 'nullable|date|after:today',
            'depreciation_rate' => 'nullable|numeric|min:0|max:100',
            'status' => [
                'required',
                Rule::in(['Active', 'In Maintenance', 'Retired', 'Lost/Stolen'])
            ],
            'notes' => 'nullable|string|max:500'
        ];
    }
    

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'serial_no.unique' => 'This serial number is already in use.',
            'location_id.exists' => 'The selected location is invalid.',
            'assigned_to.exists' => 'The selected user is invalid.',
            'warranty_expiry.after' => 'Warranty expiry must be after purchase date.',
            'country_code.size' => 'Country code must be exactly 2 characters.'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('assigned_to') && empty($this->assigned_to)) {
            $this->merge(['assigned_to' => null]);
        }

        $this->merge([
            'asset_value' => str_replace(',', '', $this->asset_value),
            'country_code' => strtoupper($this->country_code)
        ]);
    }
}