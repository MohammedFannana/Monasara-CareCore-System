<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSponsorValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sponsorId = $this->route('sponsor')?->id ?? $this->route('id');

        return [
            'name' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email', Rule::unique('sponsors', 'email')->ignore($sponsorId)],
            'country' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'receive_report' => ['sometimes', 'in:yes,no'],
            'payment_reminder' => ['sometimes', 'in:yes,no'],
            'payment_mechanism' => ['sometimes', 'in:bank,credit_card,debit_card,PALPAY,benefit_pay'],
        ];
    }
}
