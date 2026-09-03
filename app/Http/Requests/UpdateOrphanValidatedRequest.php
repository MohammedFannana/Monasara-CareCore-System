<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrphanValidatedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $orphanId = $this->route('orphan')?->id ?? $this->route('id');

        return [
            'image' => ['sometimes', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'name' => ['sometimes', 'string'],
            'birth_date' => ['sometimes', 'date'],
            'birth_place' => ['sometimes', 'string'],
            'country' => ['sometimes', 'string'],
            'city' => ['sometimes', 'string'],
            'landmark' => ['sometimes', 'string'],
            'id_number' => ['sometimes', 'numeric', Rule::unique('orphans', 'id_number')->ignore($orphanId)],
            'orphan_status' => ['sometimes', 'in:يتيم الأم,يتيم الأب,يتيم الأبوين'],
            'gender' => ['sometimes', 'in:ذكر,أنثى'],
            'nominating_authority' => ['nullable', 'string'],

            'mother_name' => ['sometimes', 'string'],
            'death_mother_date' => ['nullable', 'string'],
            'cause_mother_death' => ['nullable', 'string'],
            'father_name' => ['sometimes', 'string'],
            'death_father_date' => ['nullable', 'string'],
            'cause_father_death' => ['nullable', 'string'],
            'mother_id_number' => ['nullable', 'numeric'],
            'mother_marital_status' => ['nullable', 'string'],
            'mother_phone' => ['nullable', 'string'],
            'father_id_number' => ['nullable', 'numeric'],
            'father_marital_status' => ['nullable', 'string'],
            'father_phone' => ['nullable', 'string'],

            'father_death_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_father_death' => ['nullable', 'string'],
            'mother_death_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_mother_death' => ['nullable', 'string'],

            'guardian_name' => ['sometimes', 'string'],
            'guardian_relation' => ['sometimes', 'string'],
            'guardian_jop' => ['sometimes', 'string'],
            'guardian_id_number' => ['sometimes', 'numeric'],
            'guardian_housing' => ['sometimes', 'string'],
            'guardian_whats_phone' => ['sometimes', 'string'],
            'guardian_first_phone' => ['sometimes', 'string'],
            'guardian_secound_phone' => ['sometimes', 'string'],
            'health_status' => ['sometimes', 'in:جيد,مريض'],
            'disease_type' => ['nullable', 'in:مرض عادي,مرض مزمن,مرض عضال'],
            'medical_report' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_medical_report' => ['nullable', 'string'],

            'educational_status' => ['sometimes', 'in:دون سن الدراسة,يدرس,لا يدرس'],
            'academic_stage' => ['nullable', 'string'],
            'average' => ['nullable', 'string'],
            'educational_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_educational_certificate' => ['nullable', 'string'],

            'male_number' => ['sometimes', 'integer', 'min:0'],
            'female_number' => ['sometimes', 'integer', 'min:0'],

            'receive_guarantee' => ['sometimes', 'in:bank,wallet'],
            'account_number' => ['nullable', 'string'],
            'bank' => ['nullable', 'string'],
            'phone_number_linked_account' => ['nullable', 'string'],
            'wallet_number' => ['nullable', 'string'],
            'wallet_owner' => ['nullable', 'string'],
            'wallet_owner_id_number' => ['nullable', 'string'],
            'wallet_owner_id_number_image' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_wallet_owner_id_number_image' => ['nullable', 'string'],
        ];
    }
}
