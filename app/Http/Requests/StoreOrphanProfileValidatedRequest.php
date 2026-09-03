<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrphanProfileValidatedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'birth_place' => ['required', 'string'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'landmark' => ['required', 'string'],
            'orphan_status' => ['required', 'in:يتيم الأم,يتيم الأب,يتيم الأبوين'],
            'gender' => ['required', 'in:ذكر,أنثى'],
            'mother_name' => ['required', 'string'],
            'death_mother_date' => ['nullable', 'string'],
            'cause_mother_death' => ['nullable', 'string'],
            'father_name' => ['required', 'string'],
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
            'guardian_name' => ['required', 'string'],
            'guardian_relation' => ['required', 'string'],
            'guardian_jop' => ['required', 'string'],
            'guardian_id_number' => ['required', 'numeric'],
            'guardian_housing' => ['required', 'string'],
            'guardian_whats_phone' => ['required', 'string'],
            'guardian_first_phone' => ['required', 'string'],
            'guardian_secound_phone' => ['required', 'string'],
            'health_status' => ['required', 'in:جيد,مريض'],
            'disease_type' => ['nullable', 'in:مرض عادي,مرض مزمن,مرض عضال'],
            'medical_report' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_medical_report' => ['nullable', 'string'],
            'educational_status' => ['required', 'in:دون سن الدراسة,يدرس,لا يدرس'],
            'academic_stage' => ['nullable', 'string'],
            'average' => ['nullable', 'string'],
            'educational_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'not_available_educational_certificate' => ['nullable', 'string'],
            'male_number' => ['required', 'integer', 'min:0'],
            'female_number' => ['required', 'integer', 'min:0'],
            'receive_guarantee' => ['required', 'in:bank,wallet'],
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
