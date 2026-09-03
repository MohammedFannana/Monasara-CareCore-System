<?php

namespace App\Exports;

use App\Models\Sponsorship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SponsorshipReportExport implements FromCollection, WithHeadings
{

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        return Sponsorship::when($this->status && $this->status !== 'all', function ($query) {

                $query->where('status', $this->status);
        })
        ->select('orphan_id' , 'sponsor_id' , 'sponsorship_date' ,'duration' ,'bail_amount' , 'total' ,'status' , 'created_at')
        ->get()
        ->map(function ($item) {
            return [

                'orphan'  => $item->orphan ? $item->orphan->name : ' ',
                'orphan_id_number' => $item->orphan ? $item->orphan->id_number : ' ',
                'orphan_city' => $item->orphan ? $item->orphan->city : ' ',
                'guardian_name' => $item->orphan ? $item->orphan->guardian_name : ' ',
                'guardian_id_number' => $item->orphan?->profile?->guardian_id_number ?? ' ',
                'guardian_first_phone' => $item->orphan?->profile?->guardian_first_phone ?? ' ',

                'sponsor' => $item->sponsor ? $item->sponsor->name : ' ',


                // 'orphan'  => $item->orphan ? $item->orphan->name : ' ',
                // 'sponsor' => $item->sponsor? $item->sponsor->name : ' ',
                'sponsorship_date'    => $item->sponsorship_date,
                'created_at'    =>  $item->created_at?->format('Y-m-d H:i'),

                'duration'   => $item->duration,
                'bail_amount'   => $item->bail_amount,
                'total' => $item->total,
                'status' => $item->status,

            ];
        });

    }

    public function headings(): array
    {
        return [
                'اسم اليتيم',
                'رقم هوية اليتيم',
                'مدينة اليتيم',
                'اسم الوصي',
                'رقم هوية الوصي',
                'رقم جوال الوصي',
                'اسم الكافل',
                'تاريخ بدء الكفالة',
                'تاريخ دفع الكفالة',
                'مدة الكفالة',
                'مبلغ الكفالة الشهري',
                'المبلغ الاجمالي',
                'حالة الكفالة'
            ];
        }
}
