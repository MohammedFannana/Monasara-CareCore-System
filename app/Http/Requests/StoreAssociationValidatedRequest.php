<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreAssociationValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'responsible_person' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:associations,email'],
            'fax' => ['required', 'string', 'unique:associations,fax'],
            'license_number' => ['nullable', 'integer', 'unique:associations,license_number'],
            'phone' => ['required', 'string', 'unique:associations,phone'],
            'phone1' => ['required', 'string'],
            'phone2' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
