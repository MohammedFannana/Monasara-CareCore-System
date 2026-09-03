<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'review_number' => ['required', 'string', 'in:first,final'],
            'review_date' => ['required', 'date'],
            'orphan_id' => ['required', 'exists:orphans,id'],
            'status' => ['required', 'in:approved,rejected'],
            'report' => ['required', 'string'],
            'name' => ['required', 'string'],
        ];
    }
}
