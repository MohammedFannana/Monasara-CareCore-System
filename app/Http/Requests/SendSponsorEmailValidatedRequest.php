<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSponsorEmailValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ];
    }
}
