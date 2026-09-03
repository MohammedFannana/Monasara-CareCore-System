<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreSponsorValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:sponsors,email'],
            'country' => ['required', 'string'],
            'address' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'receive_report' => ['required', 'in:yes,no'],
            'payment_reminder' => ['required', 'in:yes,no'],
            'payment_mechanism' => ['required', 'in:bank,credit_card,debit_card,PALPAY,benefit_pay'],
        ];
    }
}
