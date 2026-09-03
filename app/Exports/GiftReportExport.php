<?php

namespace App\Exports;

use App\Models\Gift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class GiftReportExport implements FromCollection, WithHeadings
{

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function collection()
    {
        return Gift::
        select('orphan_id' , 'sponsor_id' , 'gift_date' ,'duration' ,'amount' , 'total' , 'notes')
        ->get()
        ->map(function ($item) {
            return [
                'orphan'  => $item->orphan ? $item->orphan->name : ' ',
                'sponsor' => $item->sponsor? $item->sponsor->name : ' ',
                'gift_date'    => $item->gift_date,
                'amount'   => $item->amount,
                'total' => $item->total,
                'notes' => $item->notes ? $item->notes : ' ',

            ];
        });

    }

    public function headings(): array
    {
        return ['اسم اليتيم', 'اسم الكافل' ,'تاريخ الهدية'  , 'ميلغ الهدية' , 'المبلغ الاجمالي' , 'ملاحظات الكافل'];
    }
}
