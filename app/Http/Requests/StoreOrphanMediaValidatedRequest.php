<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrphanMediaValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'media' => [
                'required',
                'file',
                'mimetypes:video/mp4,video/quicktime,image/jpeg,image/png,image/jpg',
                'max:51200',
            ],
        ];
    }
}
