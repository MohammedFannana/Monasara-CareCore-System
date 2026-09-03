<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrphanValidatedRequest extends FormRequest
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
        return [
            'image' => ['required', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576'],
            'name' => ['required', 'string'],
            'role' => ['required', 'in:candidate,auditor,rejected,certified,waiting,sponsored'],
            'association_id' => ['required', 'exists:associations,id'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['required', 'string'],
            'country' => ['required', 'string'],
            'city' => ['required', 'string'],
            'landmark' => ['required', 'string'],
            'id_number' => ['required', 'numeric', 'unique:orphans,id_number', 'digits:9'],
            'orphan_status' => ['required', 'in:يتيم الأم,يتيم الأب,يتيم الأبوين'],
            'gender' => ['required', 'in:ذكر,أنثى'],
            'nominating_authority' => ['nullable', 'string'],

            'mother_name' => ['required', 'string'],
            'death_mother_date' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأبوين,يتيم الأم'],
            'cause_mother_death' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأبوين,يتيم الأم'],
            'father_name' => ['required', 'string'],
            'death_father_date' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأبوين,يتيم الأب'],
            'cause_father_death' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأبوين,يتيم الأب'],
            'mother_id_number' => ['nullable', 'numeric', 'required_if:orphan_status,يتيم الأب'],
            'mother_marital_status' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأب'],
            'mother_phone' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأب'],
            'father_id_number' => ['nullable', 'numeric', 'required_if:orphan_status,يتيم الأم'],
            'father_marital_status' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأم'],
            'father_phone' => ['nullable', 'string', 'required_if:orphan_status,يتيم الأم'],

            'not_available_father_death' => ['nullable', 'string', 'required_without:father_death_certificate'],
            'mother_death_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576', 'required_without:not_available_mother_death'],
            'not_available_mother_death' => ['nullable', 'string', 'required_without:mother_death_certificate'],

            'guardian_name' => ['required', 'string'],
            'guardian_relation' => ['required', 'string'],
            'guardian_jop' => ['required', 'string'],
            'guardian_id_number' => ['required', 'numeric'],
            'guardian_housing' => ['required', 'string'],
            'guardian_whats_phone' => ['required', 'string'],
            'guardian_first_phone' => ['required', 'string'],
            'guardian_secound_phone' => ['required', 'string'],
            'health_status' => ['required', 'in:جيد,مريض'],
            'disease_type' => ['nullable', 'in:مرض عادي,مرض مزمن,مرض عضال', 'required_if:health_status,مريض'],

            'medical_report' => [
                'nullable', 'image', 'dimensions:min_width=100,min_height=100',
                'max:1048576',
                function ($attribute, $value, $fail) {
                    if ($this->health_status === 'مريض') {
                        if (empty($value) && empty($this->not_available_medical_report)) {
                            $fail('يجب تقديم التقرير الطبي أو سبب عدم التوفر.');
                        }
                    }
                },
            ],

            'not_available_medical_report' => [
                'nullable', 'string',
                function ($attribute, $value, $fail) {
                    if ($this->health_status === 'مريض') {
                        if (empty($value) && empty($this->medical_report)) {
                            $fail('يجب تقديم صورة التقرير الطبي أو سبب عدم التوفر.');
                        }
                    }
                },
            ],

            'educational_status' => ['required', 'in:دون سن الدراسة,يدرس,لا يدرس'],
            'academic_stage' => ['nullable', 'string', 'required_if:educational_status,يدرس'],
            'average' => ['nullable', 'string', 'required_if:educational_status,يدرس'],

            'educational_certificate' => ['nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576',
                function ($attribute, $value, $fail) {
                    if ($this->educational_status === 'يدرس') {
                        if (empty($value) && empty($this->not_available_educational_certificate)) {
                            $fail('يجب تقديم الشهادة الدراسية أو سبب عدم التوفر.');
                        }
                    }
                }
            ],

            'not_available_educational_certificate' => ['nullable', 'string',
                function ($attribute, $value, $fail) {
                    if ($this->educational_status === 'يدرس') {
                        if (empty($value) && empty($this->educational_certificate)) {
                            $fail('يجب تقديم صورة الشهادة الدراسية أو سبب عدم التوفر.');
                        }
                    }
                }
            ],

            'male_number' => ['required', 'integer', 'min:0'],
            'female_number' => ['required', 'integer', 'min:0'],

            'receive_guarantee' => ['required', 'in:bank,wallet'],
            'account_number' => ['nullable', 'string', 'required_if:receive_guarantee,bank'],
            'bank' => ['nullable', 'string', 'required_if:receive_guarantee,bank'],
            'phone_number_linked_account' => ['nullable', 'string', 'required_if:receive_guarantee,bank'],
            'wallet_number' => ['nullable', 'string', 'required_if:receive_guarantee,wallet'],
            'wallet_owner' => ['nullable', 'string', 'required_if:receive_guarantee,wallet'],
            'wallet_owner_id_number' => ['nullable', 'string', 'required_if:receive_guarantee,wallet'],
            'wallet_owner_id_number_image' => [
                'nullable', 'image', 'dimensions:min_width=100,min_height=100', 'max:1048576',
                function ($attribute, $value, $fail) {
                    if ($this->receive_guarantee === 'wallet') {
                        if (empty($value) && empty($this->not_available_wallet_owner_id_number_image)) {
                            $fail('يجب تقديم صورة الهوية أو سبب عدم التوفر.');
                        }
                    }
                }
            ],
            'not_available_wallet_owner_id_number_image' => [
                'nullable', 'string',
                function ($attribute, $value, $fail) {
                    if ($this->receive_guarantee === 'wallet') {
                        if (empty($value) && empty($this->wallet_owner_id_number_image)) {
                            $fail('يجب تقديم صورة الهوية أو سبب عدم التوفر.');
                        }
                    }
                }
            ],
        ];
    }
}
