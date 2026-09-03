<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'duration' => ['sometimes', 'numeric', 'min:1'],
            'bail_amount' => ['sometimes', 'numeric', 'min:1'],
            'payment_received' => ['sometimes', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'delivery_bail' => ['sometimes', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'thank_letter_video' => ['nullable', 'url'],
            'thank_letter_audio' => ['nullable', 'file'],
        ];
    }
}
