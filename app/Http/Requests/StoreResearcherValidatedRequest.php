<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreResearcherValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'association_id' => ['required', 'exists:associations,id'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:researchers,email'],
            'id_number' => ['required', 'numeric', 'unique:researchers,id_number'],
            'phone' => ['required', 'string'],
            'phone_whats' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
    }
}
