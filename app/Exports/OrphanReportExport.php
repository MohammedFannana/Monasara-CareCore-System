<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrphanReportExport implements FromCollection, WithHeadings
{
    protected $orphans;

    public function __construct($orphans)
    {
        $this->orphans = $orphans;
    }

    public function collection()
    {

        return $this->orphans->map(function ($orphan) {
            $lastSponsorship = $orphan->sponsorships->first();
            return [
                'name' => $orphan->name,
                'association_id ' => $orphan->association->name,
                'birth_date' => $orphan->birth_date,
                'birth_place' => $orphan->birth_place,
                'country' => $orphan->country,
                'city' => $orphan->city,
                'landmark' => $orphan->landmark,
                'id_number ' => $orphan->id_number ,
                'orphan_status' => $orphan->orphan_status,
                'gender' => $orphan->gender,
                'health_status' => optional($orphan->profile)->health_status,
                'educational_status' => optional($orphan->profile)->educational_status,
                'guardian_name' => $orphan->guardian_name,
                'guardian_id_number' => optional($orphan->profile)->guardian_id_number,
                'guardian_first_phone' => optional($orphan->profile)->guardian_first_phone,
                'account_number' => optional($orphan->profile)->account_number,
                'wallet_number' =>optional($orphan->profile)->wallet_number,
                'sponsor_name' => $lastSponsorship?->sponsor?->name,
                'sponsor_date' => $lastSponsorship?->sponsorship_date,

            ];
        });
    }

    public function headings(): array
    {
        return ['اسم اليتيم', ' اسم الجمعية ' , ' تاريخ الميلاد ', 'مكان الميلاد', 'الدولة',
        'المدينة' , 'أقرب معلم' ,'رقم الهوية' , 'حالة اليتيم' , 'الجنس',
        'الحالة الصحية' , 'الحالة التعليمية' , 'اسم الوصي' , 'رقم هوية الوصي' , 'رقم جوال الوصي' ,'الحساب البنكي' , 'رقم المحفظة' , 'اسم الكافل' , 'تاريخ بدأ الكفالة'
        ];
    }
}
