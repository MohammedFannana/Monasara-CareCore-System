<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PendingSponsorshipValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orphan_ids' => ['required', 'array', 'min:1'],
            'duration' => ['required', 'integer', 'min:1'],
            'type' => ['required', 'in:sponsorship,gift'],
            'bail_amount' => [
                'required',
                'numeric',
                'min:1',
                Rule::when($this->type === 'sponsorship', ['min:20']),
            ],
            'notes' => ['nullable', 'string'],
        ];
    }
}
