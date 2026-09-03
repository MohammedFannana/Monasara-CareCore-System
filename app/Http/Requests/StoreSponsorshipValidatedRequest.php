<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSponsorshipValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'duration' => ['required', 'numeric', 'min:1'],
            'bail_amount' => ['required', 'numeric', 'min:1', Rule::when($this->type === 'sponsorship', ['min:20'])],
            'type' => ['required', 'in:sponsorship,gift'],
            'notes' => ['nullable', 'string'],
            'payment_received' => ['nullable', 'image', 'max:1048576'],
        ];
    }
}
