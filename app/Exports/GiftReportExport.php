<?php

namespace App\Exports;

use App\Models\Gift;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class GiftReportExport implements FromCollection, WithHeadings
{

    protected $status;
    protected ?int $associationId;

    public function __construct($status, ?int $associationId = null)
    {
        $this->status = $status;
        $this->associationId = $associationId;
    }

    public function collection()
    {
        return Gift::with(['orphan', 'sponsor'])
        ->when($this->associationId, fn ($query) => $query->whereHas('orphan', fn ($query) => $query->where('association_id', $this->associationId)))
        ->select('order_id', 'orphan_id' , 'sponsor_id' , 'gift_date' ,'duration' ,'amount' , 'total' , 'notes')
        ->get()
        ->map(function ($item) {
            return [
                'order_id' => $item->order_id,
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
        return ['رقم معرف الطلب', 'اسم اليتيم', 'اسم الكافل' ,'تاريخ الهدية'  , 'ميلغ الهدية' , 'المبلغ الاجمالي' , 'ملاحظات الكافل'];
    }
}
