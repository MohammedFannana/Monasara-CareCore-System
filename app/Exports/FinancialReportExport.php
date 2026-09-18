<?php
namespace App\Exports;

use App\Models\Gift;
use App\Models\Sponsorship;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialReportExport implements FromCollection, WithHeadings
{
    protected $status;
    protected ?int $associationId;

    public function __construct($status = null, ?int $associationId = null)
    {
        $this->status = $status;
        $this->associationId = $associationId;
    }

    public function collection()
    {

        $gifts = Gift::with(['orphan', 'sponsor'])
            ->when($this->associationId, fn ($query) => $query->whereHas('orphan', fn ($query) => $query->where('association_id', $this->associationId)))
            ->get()
            ->map(function ($gift) {
                return [
                    'order_id'    => $gift->order_id,
                    'date'        => $gift->gift_date,
                    'owner'       => $gift->sponsor?->name ,
                    'orphan'      => $gift->orphan?->name,
                    'amount'      => $gift->amount,
                    'duration'    => $gift->duration ?? 1,
                    'total'       => $gift->total,
                    'type'        => 'هدية',
                ];
            });


        $sponsorships = Sponsorship::with(['orphan', 'sponsor'])
            ->when($this->associationId, fn ($query) => $query->whereHas('orphan', fn ($query) => $query->where('association_id', $this->associationId)))
            ->whereNull('payment_received')
            ->whereNotNull('bail_amount')
            ->get()
            ->map(function ($sponsorship) {
                return [
                    'order_id'    => $sponsorship->order_id,
                    'date'        => $sponsorship->created_at,
                    'owner'       => $sponsorship->sponsor?->name,
                    'orphan'      => $sponsorship->orphan?->name,
                    'amount'      => $sponsorship->bail_amount,
                    'duration'    => $sponsorship->duration,
                    'total'       => $sponsorship->total,
                    'type'        => 'كفالة',
                ];
            });


        return $gifts->merge($sponsorships);
    }

    public function headings(): array
    {
        return [
            'رقم معرف الطلب',
            'التاريخ',
            'اسم صاحب العملية',
            'اسم اليتيم',
            'المبلغ',
            'المدة',
            'إجمالي المبلغ',
            'النوع'
        ];
    }
}
