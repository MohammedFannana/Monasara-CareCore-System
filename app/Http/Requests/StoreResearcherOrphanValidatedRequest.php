<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResearcherOrphanValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'birth_date' => ['required'],
            'id_number' => ['required', 'numeric', 'digits:9', 'unique:orphans,id_number'],
            'nominating_authority' => ['nullable', 'string'],
            'association_id' => ['required', 'exists:associations,id'],
        ];
    }
}
