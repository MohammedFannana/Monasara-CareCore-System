<?php

namespace App\Exports;

use App\Models\Sponsorship;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SponsorshipReportExport implements FromCollection, WithHeadings
{

    protected $status;

    public function __construct($status, protected ?int $associationId = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        return Sponsorship::with(['orphan.profile', 'sponsor'])
        ->when($this->status && $this->status !== 'all', function ($query) {

                $query->where('status', $this->status);
        })
        ->when($this->associationId, fn ($query) => $query->whereHas('orphan', fn ($query) => $query->where('association_id', $this->associationId)))
        ->select('order_id', 'orphan_id' , 'sponsor_id' , 'sponsorship_date' ,'duration' ,'bail_amount' , 'total' ,'status' , 'sponsorship_delivery', 'created_at')
        ->orderBy('orphan_id')
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($item) {
            return [

                'order_id' => $item->order_id,
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
                'sponsorship_delivery' => $item->sponsorship_delivery,

            ];
        });

    }

    public function headings(): array
    {
        return [
            'رقم معرف الطلب',
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
                'حالة الكفالة',
                'تسليم الكفالة'
            ];
        }
}
