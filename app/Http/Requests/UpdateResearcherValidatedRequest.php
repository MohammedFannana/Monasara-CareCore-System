<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResearcherValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $researcherId = $this->route('researcher')?->id ?? $this->route('id');

        return [
            'name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', Rule::unique('researchers', 'email')->ignore($researcherId)],
            'id_number' => ['sometimes', 'numeric', Rule::unique('researchers', 'id_number')->ignore($researcherId)],
            'phone' => ['sometimes', 'string'],
            'phone_whats' => ['sometimes', 'string'],
        ];
    }
}
