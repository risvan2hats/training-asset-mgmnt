<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MoveAssetRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        return [
            'location_id' => [
                'required',
                'exists:locations,id',
            ],
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'location_id.not_in' => 'The asset is already at this location.',
            'move_date.before_or_equal' => 'Move date cannot be in the future.'
        ];
    }
}