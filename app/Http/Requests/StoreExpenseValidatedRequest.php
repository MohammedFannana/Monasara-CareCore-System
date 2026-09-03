<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orphan_id' => ['required', 'exists:orphans,id'],
            'duration' => ['required', 'numeric', 'min:1'],
            'bail_amount' => ['required', 'numeric', 'min:1'],
            'payment_received' => ['required', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'delivery_bail' => ['required', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'thank_letter_video' => ['nullable', 'url'],
            'thank_letter_audio' => ['nullable', 'file'],
        ];
    }
}
