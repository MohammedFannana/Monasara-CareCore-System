<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssociationValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $associationId = $this->route('association')?->id ?? $this->route('id');

        return [
            'name' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'responsible_person' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', Rule::unique('associations', 'email')->ignore($associationId)],
            'fax' => ['sometimes', 'string', Rule::unique('associations', 'fax')->ignore($associationId)],
            'license_number' => ['nullable', 'integer', Rule::unique('associations', 'license_number')->ignore($associationId)],
            'phone' => ['sometimes', 'string', Rule::unique('associations', 'phone')->ignore($associationId)],
            'phone1' => ['sometimes', 'string'],
            'phone2' => ['sometimes', 'string'],
        ];
    }
}
